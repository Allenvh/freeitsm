<?php
/**
 * API: the write-back log for one CardDAV address book.
 * GET ?provider_id=N[&limit=N]
 *
 * Every attempt to send a contact back — succeeded, refused, or failed — newest
 * first, with the server's own response kept verbatim.
 *
 * 🔑 The raw response is returned deliberately. When an operator says "it is not
 * writing", the reason is almost always in the DAV server's error body, and
 * FreeITSM's paraphrase of it is no use to somebody debugging a server this
 * install cannot log in to. System admins only, like every other endpoint on
 * this screen.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php'; // System admins only (issue #34)
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

try {
    $conn = connectToDatabase();

    $providerId = (int)($_GET['provider_id'] ?? 0);
    if ($providerId <= 0) {
        echo json_encode(['success' => false, 'error' => 'A provider is required.']);
        exit;
    }

    // ⚠️ Confirm the provider is a CardDAV one before returning anything. Not a
    // security boundary — this endpoint is already admin-only — but it stops a
    // mistyped id quietly returning an empty list that reads as "nothing has
    // ever been written", which is a different and much more alarming answer.
    $chk = $conn->prepare("SELECT id FROM auth_providers WHERE id = ? AND protocol = 'carddav'");
    $chk->execute([$providerId]);
    if (!$chk->fetch()) {
        echo json_encode(['success' => false, 'error' => 'That is not a CardDAV address book.']);
        exit;
    }

    $limit = isset($_GET['limit']) ? max(1, min(500, (int)$_GET['limit'])) : 100;

    $stmt = $conn->prepare(
        "SELECT l.id, l.user_id, l.display_name, l.outcome, l.fields, l.http_status,
                l.server_response, l.message, l.created_datetime,
                a.full_name AS analyst_name
           FROM carddav_write_log l
      LEFT JOIN analysts a ON a.id = l.triggered_by_analyst_id
          WHERE l.provider_id = ?
       ORDER BY l.id DESC
          LIMIT $limit"
    );
    $stmt->execute([$providerId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as &$r) {
        $r['id']          = (int)$r['id'];
        $r['user_id']     = $r['user_id'] !== null ? (int)$r['user_id'] : null;
        $r['http_status'] = $r['http_status'] !== null ? (int)$r['http_status'] : null;
    }
    unset($r);

    // A per-outcome tally, so the screen can say "3 refused this week" without
    // the caller counting rows it may not have all of.
    $tally = $conn->prepare(
        "SELECT outcome, COUNT(*) c FROM carddav_write_log WHERE provider_id = ? GROUP BY outcome"
    );
    $tally->execute([$providerId]);
    $counts = [];
    foreach ($tally->fetchAll(PDO::FETCH_ASSOC) as $t) $counts[$t['outcome']] = (int)$t['c'];

    echo json_encode(['success' => true, 'entries' => $rows, 'counts' => $counts]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
