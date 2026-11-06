<?php
/**
 * API: War room — search the conversations you can see.
 *
 * GET ?q=<text>&channel_id=<int|blank>
 *
 * Scoping happens inside the query (see warRoomSearch), not by filtering results
 * afterwards. Filtering afterwards is the version that looks correct and quietly
 * starves the result set when the matches sit in channels you cannot read.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/i18n.php';
require_once '../../includes/warroom.php';

header('Content-Type: application/json');

requireModuleAccessJson('war-room');

try {
    $conn      = connectToDatabase();
    $analystId = (int) $_SESSION['analyst_id'];
    I18n::initFromSession();

    $q         = trim((string) ($_GET['q'] ?? ''));
    $channelId = (isset($_GET['channel_id']) && $_GET['channel_id'] !== '') ? (int) $_GET['channel_id'] : null;

    // Searching one channel still needs the access check: the scope inside the
    // query would return nothing anyway, but a 403 says "not yours" rather than
    // "no matches", which is the difference between a permission and a lie.
    if ($channelId !== null && !warRoomCanAccessChannel($conn, $analystId, $channelId)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'No access to that channel']);
        exit;
    }

    if ($q === '') {
        echo json_encode(['success' => true, 'results' => [], 'query' => '']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'query'   => $q,
        'results' => warRoomSearch($conn, $analystId, $q, $channelId),
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Could not search']);
}
