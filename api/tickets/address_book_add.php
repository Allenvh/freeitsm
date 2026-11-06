<?php
/**
 * API: add a person to a CardDAV address book (#133).
 *
 *   GET  ?user_id=N              which address books they could be added to, and
 *                                what the new card would carry
 *   POST {user_id, provider_id}  create the card and link the person to it
 *
 * Offered only for somebody linked to no source, with an email address, and
 * only address books whose administrator switched on BOTH write-back and
 * "let analysts add people". See includes/carddav_create.php for the checks
 * made on the way (duplicates, and that the next import will see the card).
 *
 * Same gate as the people editors that show the button: the Tickets module,
 * and a person this analyst can reach.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/carddav_create.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');

try {
    $conn = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];

    $in = $_SERVER['REQUEST_METHOD'] === 'POST'
        ? (json_decode(file_get_contents('php://input'), true) ?? [])
        : $_GET;
    $userId = (int)($in['user_id'] ?? 0);

    // One answer for "does not exist" and "not yours to see", so this cannot be
    // used to find out which people exist in companies the analyst cannot see.
    if ($userId <= 0 || !analystCanAccessUser($conn, $analystId, $userId)) {
        echo json_encode(['success' => false, 'error' => 'That person could not be found.']);
        exit;
    }
    $st = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $st->execute([$userId]);
    $user = $st->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'That person could not be found.']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $targets = cardDavCreateTargets($conn, $user);
        $books = [];
        foreach ($targets['books'] as $b) {
            $pv = cardDavCreatePreview($conn, $user, $b);
            $books[] = [
                'id'          => (int)$b['id'],
                'name'        => $b['display_name'],
                'fields'      => $pv['fields'],
                'scope'       => $pv['scope'],
                'scope_value' => $pv['scope_value'],
            ];
        }
        echo json_encode(['success' => true, 'books' => $books, 'reason' => $targets['reason']]);
        exit;
    }

    $res = cardDavCreateContact($conn, $userId, (int)($in['provider_id'] ?? 0), $analystId);
    echo json_encode([
        'success'  => $res['ok'],
        'code'     => $res['code'],
        'error'    => $res['error'],
        'existing' => $res['existing'],
    ]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
