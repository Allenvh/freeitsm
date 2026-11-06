<?php
/**
 * API: War room — what is waiting for me.
 *
 * GET  → { count: <int>, mentions: [ … ] }
 *
 * WHY THIS EXISTS SEPARATELY FROM poll.php. A mention is only worth anything if it
 * reaches somebody who is NOT looking at the war room — otherwise it is decoration
 * on a page they were already reading. So this is called by the shared header, on
 * every page in FreeITSM, and it is built to be the cheapest request in the app:
 *
 *   - one indexed lookup on (analyst_id, id), no joins the panel does not render;
 *   - a 60-second interval, not the war room's 3 seconds;
 *   - the header skips it entirely on the war room page itself, which already
 *     polls and would otherwise ask twice;
 *   - it stops while the tab is hidden.
 *
 * That budget matters more here than usual: this runs on every page during exactly
 * the incident when the rest of the app is busiest, which is the same reasoning
 * that ruled out SSE for the chat itself.
 *
 * ⚠️ Returns an empty result rather than 403 for an analyst without the module, so
 * the header never has to know who does. The header renders nothing for count 0.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/i18n.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['count' => 0, 'mentions' => []]);
    exit;
}

try {
    $conn      = connectToDatabase();
    $analystId = (int) $_SESSION['analyst_id'];

    if (!analystCanAccessModule($conn, $analystId, 'war-room')) {
        echo json_encode(['count' => 0, 'mentions' => []]);
        exit;
    }

    require_once '../../includes/warroom.php';
    I18n::initFromSession();

    $mentions = warRoomMyMentions($conn, $analystId, 15);
    echo json_encode(['count' => count($mentions), 'mentions' => $mentions]);
} catch (Throwable $e) {
    // A failure here must never break the page it is embedded in. Silence is the
    // right answer: the war room itself is still reachable from the waffle menu.
    echo json_encode(['count' => 0, 'mentions' => []]);
}
