<?php
/**
 * Microsoft Graph change-notification endpoint (GH #75).
 *
 * Graph POSTs here when a subscribed calendar changes, so a move or a deletion
 * lands in seconds rather than on the next poll.
 *
 * 🔴 THIS URL IS PUBLIC AND UNAUTHENTICATED BY NECESSITY. Microsoft's servers
 * call it and cannot carry a session or an API key. Three things keep it safe,
 * and all three matter:
 *
 *   1. clientState — a random secret per subscription, echoed in every
 *      notification. A payload without a MATCHING one is discarded. This is what
 *      separates Graph from anyone on the internet who finds the URL.
 *   2. The body is never trusted for CONTENT. A notification says only "this
 *      subscription saw a change"; what actually changed is then read from Graph
 *      ourselves, with the app's own credentials. So a forged notification can
 *      at worst cause a poll of a calendar we already sync.
 *   3. Nothing here writes a ticket directly. It calls the same pull path as the
 *      cron, with the same guards — baseline, deletion cap, audit.
 *
 * ⚠️ THE VALIDATION HANDSHAKE MUST BE FAST AND UNCONDITIONAL. When a
 * subscription is created Graph immediately GETs/POSTs here with
 * ?validationToken=… and expects that exact string back, as text/plain, within
 * ten seconds. It happens BEFORE any subscription exists, so it cannot be
 * authenticated by clientState — echoing a token proves only that we control the
 * URL, which is precisely what it is for.
 *
 * 🔴 AND IT MUST COME BEFORE THE require LINES. Graph sends that validation POST
 * declaring `Content-Type: text/plain`, and includes/request_guard.php — reached
 * via functions.php, running on include — answers 415 to exactly that. Two
 * correct pieces of code, colliding: the guard refuses text/plain because no
 * browser has an honest reason to send it, and Graph sends it because the body is
 * empty and the token is in the query string. Put the handshake below the
 * includes and it never executes; Graph reports only "Subscription validation
 * request failed … UnsupportedMediaType" and no subscription is ever created.
 *
 * Answering above the guard is safe because this branch does nothing: no session,
 * no database, no cookie read, no state changed. It echoes one query parameter as
 * inert text/plain (nosniff, so no browser can be talked into running it) and
 * exits. There is nothing here for a forged request to achieve.
 */

// ── 1. Validation handshake ─────────────────────────────────────────────────
// Genuinely first: before the includes, for the reason in the header above.
if (isset($_GET['validationToken'])) {
    header('Content-Type: text/plain; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    http_response_code(200);
    echo $_GET['validationToken'];
    exit;
}

// No session: this is a machine-to-machine callback. Real notifications arrive as
// application/json and pass the guard normally, so these stay where they are.
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/services/tickets.php';
require_once __DIR__ . '/../../includes/calendar_sync/pull.php';

// ── 2. Acknowledge FAST, then work ──────────────────────────────────────────
// Graph expects a 202 within 3 seconds and retries if it does not get one —
// which would mean polling the same calendar several times over. Answer first,
// then do the work with the connection already closed.
$raw = file_get_contents('php://input');
http_response_code(202);
header('Content-Type: text/plain; charset=utf-8');
echo 'Accepted';
if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
} else {
    // Not available under every SAPI; the work still runs, Graph just waits.
    @ob_end_flush();
    @flush();
}

$payload = json_decode($raw, true);
if (!is_array($payload) || empty($payload['value'])) exit;

try {
    $conn = connectToDatabase();
    if (!calendarSyncSchemaReady($conn)) exit;

    // One notification batch can name several subscriptions, and a busy calendar
    // produces several for the same one. Poll each analyst ONCE.
    $analysts = [];
    foreach ($payload['value'] as $note) {
        $subId  = (string)($note['subscriptionId'] ?? '');
        $state  = (string)($note['clientState'] ?? '');
        if ($subId === '' || $state === '') continue;

        $st = $conn->prepare(
            "SELECT analyst_id, subscription_secret FROM calendar_enrolments
              WHERE subscription_id = ? AND mode = 'push'"
        );
        $st->execute([$subId]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        if (!$row) continue;                       // unknown subscription

        // 🔑 hash_equals, not ==. A timing-safe comparison on the one secret
        // standing between this endpoint and the open internet.
        if (!hash_equals((string)$row['subscription_secret'], $state)) {
            error_log('calendar graph_notify: clientState mismatch for subscription ' . $subId);
            continue;
        }
        $analysts[(int)$row['analyst_id']] = true;
    }

    foreach (array_keys($analysts) as $analystId) {
        // The SAME path the cron uses, guards and all. A notification changes
        // WHEN we look, never WHAT we are willing to do.
        calendarSyncPullForAnalyst($conn, $analystId);
    }
} catch (Exception $e) {
    error_log('calendar graph_notify: ' . $e->getMessage());
}
