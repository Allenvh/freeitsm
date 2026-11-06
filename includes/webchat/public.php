<?php
/**
 * Web chat — public request plumbing.
 *
 * Required by the unauthenticated widget endpoints (api/webchat/{config,start,send,poll}).
 * These are the only FreeITSM endpoints a random website visitor reaches, so everything
 * that keeps them safe lives here: CORS scoped to the widget's own origin allowlist, the
 * widget-key lookup, per-conversation token loading, and basic rate limiting.
 *
 * Trust model: the widget key only lets you START a conversation (and only from an allowed
 * origin). Reading or posting to an existing conversation needs its per-conversation TOKEN,
 * which lives in that one visitor's browser — so the public key leaking (it always does; it
 * ships in page source) never exposes anyone's chat.
 */

require_once __DIR__ . '/webchat.php';

/** A fresh per-conversation capability token (browser-held). */
function webchatGenerateToken(): string
{
    return 'wct_' . bin2hex(random_bytes(24));
}

/** Best-effort client IP (rate limiting only — not a security boundary). */
function webchatClientIp(): string
{
    return (string) ($_SERVER['REMOTE_ADDR'] ?? '');
}

/**
 * The origin of the requesting page. Cross-origin requests carry an Origin header (the
 * authoritative case in production, where the customer's site and the FreeITSM host are
 * different origins). Same-origin requests — e.g. a demo site on the same host — omit
 * Origin entirely, so we fall back to the scheme+host of the Referer. Returns '' if
 * neither is present. Like the whole allowlist, this is a deterrent, not a crypto control.
 */
function webchatRequestOrigin(): string
{
    $o = trim((string) ($_SERVER['HTTP_ORIGIN'] ?? ''));
    if ($o !== '') {
        return rtrim($o, '/');
    }
    $ref = trim((string) ($_SERVER['HTTP_REFERER'] ?? ''));
    if ($ref !== '') {
        $p = parse_url($ref);
        if (!empty($p['scheme']) && !empty($p['host'])) {
            $origin = $p['scheme'] . '://' . $p['host'];
            if (!empty($p['port'])) {
                $origin .= ':' . $p['port'];
            }
            return $origin;
        }
    }
    return '';
}

/**
 * Emit CORS + JSON headers. Pass the request's origin to echo it back (required for the
 * browser to accept the response on a cross-origin call); pass '*' when there is no
 * origin (same-origin / curl). Call once the origin has been authorised.
 *
 * @param ?string $allowOrigin  the origin to echo in Access-Control-Allow-Origin, or '*'; null or '' sends none
 */
function webchatPublicHeaders(?string $allowOrigin = null): void
{
    header('Content-Type: application/json');
    if ($allowOrigin !== null && $allowOrigin !== '') {
        header('Access-Control-Allow-Origin: ' . $allowOrigin);
        header('Vary: Origin');
    }
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Max-Age: 600');
}

/** Answer a CORS preflight and exit. Call at the very top of each public endpoint. */
function webchatHandlePreflight(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
        // Echo the requesting origin so the real request that follows is allowed; the
        // actual origin allowlist is enforced on that real request, not the preflight.
        webchatPublicHeaders($_SERVER['HTTP_ORIGIN'] ?? '*');
        http_response_code(204);
        exit;
    }
}

/**
 * Emit a JSON error and stop.
 *
 * @param string $msg  the error text for the widget, safe to show a visitor
 * @param int $code  the HTTP status code
 */
function webchatFail(string $msg, int $code = 400): void
{
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

/**
 * Load the widget for a public request, enforce active + origin allowlist, and set the
 * CORS headers. Returns the widget row (joined to its channel). Exits with JSON on any
 * failure — so the caller can treat the return as authorised.
 *
 * @param string $key  the public widget key from the request - untrusted; '' fails with 404
 */
function webchatPublicResolve(PDO $conn, string $key): array
{
    $origin = webchatRequestOrigin();

    $widget = $key !== '' ? webchatLoadByKey($conn, $key) : null;
    if (!$widget || empty($widget['is_active'])) {
        webchatPublicHeaders();
        webchatFail('Unknown or inactive chat widget', 404);
    }

    $allowed = webchatParseOrigins($widget['allowed_origins'] ?? null);
    if (!webchatOriginAllowed($allowed, $origin)) {
        // Don't echo an Allow-Origin — the browser blocks the read, and we 403 for curl.
        webchatPublicHeaders();
        webchatFail('This site is not permitted to use this chat widget', 403);
    }

    // Authorised — echo the origin (or '*' when there isn't one) so the response is read.
    webchatPublicHeaders($origin !== '' ? $origin : '*');
    return $widget;
}

/**
 * Load a conversation by its token, scoped to the widget's channel (so a token issued
 * for one widget can't be replayed against another). Null if not found.
 *
 * @param string $token  the conversation token the widget holds - untrusted; '' gives null
 * @param int $channelId  the widget's messaging_channels id the conversation must belong to
 */
function webchatLoadConversation(PDO $conn, string $token, int $channelId): ?array
{
    if ($token === '') {
        return null;
    }
    try {
        $stmt = $conn->prepare(
            "SELECT * FROM webchat_conversations WHERE token = ? AND channel_id = ? LIMIT 1"
        );
        $stmt->execute([$token, $channelId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception) {
        return null;
    }
    return $row ?: null;
}

/**
 * Cap new conversations per IP per minute. Exits (429) on breach.
 *
 * @param string $ip  the visitor's IP address; '' skips the check
 */
function webchatRateLimitStart(PDO $conn, string $ip): void
{
    if ($ip === '') {
        return;
    }
    try {
        $st = $conn->prepare(
            "SELECT COUNT(*) FROM webchat_conversations
             WHERE visitor_ip = ? AND created_datetime > (UTC_TIMESTAMP() - INTERVAL 1 MINUTE)"
        );
        $st->execute([$ip]);
        if ((int) $st->fetchColumn() >= 5) {
            webchatFail('Too many chats started — please wait a moment and try again', 429);
        }
    } catch (Exception) { /* table missing → don't block */ }
}

/** Cap inbound messages per conversation per minute. Exits (429) on breach. */
function webchatRateLimitSend(PDO $conn, int $ticketId): void
{
    if ($ticketId <= 0) {
        return;
    }
    try {
        $st = $conn->prepare(
            "SELECT COUNT(*) FROM emails
             WHERE ticket_id = ? AND channel = 'webchat' AND direction = 'Inbound'
               AND received_datetime > (UTC_TIMESTAMP() - INTERVAL 1 MINUTE)"
        );
        $st->execute([$ticketId]);
        if ((int) $st->fetchColumn() >= 20) {
            webchatFail('You are sending messages too quickly — please slow down', 429);
        }
    } catch (Exception) { /* don't block */ }
}
