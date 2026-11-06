<?php
/**
 * API: Can this account WRITE to the chosen address book?
 * POST JSON { id?, carddav_url, carddav_username, carddav_password, carddav_auth,
 *             carddav_addressbook }
 *
 * 🔑 THIS ASKS THE SERVER. FreeITSM keeps no list of "CardDAV servers we support
 * writing to", and should not: writing is a plain `PUT`, defined by RFC 6352
 * alongside the `PROPFIND` and `REPORT` the import already uses, so any server
 * the import works against can in principle be written to. What genuinely varies
 * is PERMISSION — a shared, subscribed or published address book is very
 * commonly read-only, and the alternative to asking up front is finding out by
 * failing in the middle of somebody's edit.
 *
 * 🔴 IT WRITES NOTHING. The check is a `PROPFIND` for
 * `current-user-privilege-set`, which reports what the account is allowed to do.
 * Testing by writing a card and deleting it again would be a far worse idea than
 * it sounds: the delete can fail, and the operator's address book is left with a
 * contact called something like "FreeITSM test".
 *
 * A blank or masked password means "use the one already stored for provider
 * `id`", so this works without re-typing the secret — same rule as the
 * connection test beside it.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php'; // System admins only (issue #34)
require_once '../../includes/functions.php';
require_once '../../includes/encryption.php';
require_once '../../includes/carddav_write.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    echo json_encode(['success' => false, 'error' => 'Invalid request data']);
    exit;
}

try {
    $conn = connectToDatabase();

    $password = $data['carddav_password'] ?? '';
    if (isMaskedNoChangeValue($password) && !empty($data['id'])) {
        $stmt = $conn->prepare("SELECT carddav_password FROM auth_providers WHERE id = ? AND protocol = 'carddav'");
        $stmt->execute([(int)$data['id']]);
        $stored = (string)($stmt->fetchColumn() ?: '');
        $password = $stored !== '' ? decryptValue($stored) : '';
    }

    $url  = trim($data['carddav_url'] ?? '');
    $book = trim($data['carddav_addressbook'] ?? '');
    // Read once — see the long note in test_carddav_connection.php about why an
    // "Undefined array key" warning here breaks the caller's JSON.parse().
    $authInput = $data['carddav_auth'] ?? 'auto';
    $auth = in_array($authInput, ['auto', 'digest', 'basic'], true) ? $authInput : 'auto';

    if ($url === '') {
        echo json_encode(['success' => false, 'error' => 'Enter the address of your CardDAV server first.']);
        exit;
    }
    if ($book === '') {
        // The privilege set is a property of the BOOK, not of the server, and a
        // server where one book is writable and another is not is the normal
        // case rather than an exotic one.
        echo json_encode([
            'success' => false,
            'error'   => 'Choose an address book first — permission is granted per book, so there is nothing to check until one is picked.',
        ]);
        exit;
    }

    $cfg = ['url' => $url, 'username' => trim($data['carddav_username'] ?? ''),
            'password' => $password, 'auth' => $auth];

    $res = cardDavCanWrite($cfg, $book);

    if ($res['error'] !== '' && !$res['known']) {
        echo json_encode([
            'success'  => true,
            'writable' => false,
            'known'    => false,
            // ⚠️ success:true with writable:false. The CHECK worked; the answer
            // is just no, or unknown. Returning success:false would render as
            // "the test failed", sending somebody to look at their password.
            'message'  => $res['error'],
        ]);
        exit;
    }

    echo json_encode([
        'success'    => true,
        'writable'   => $res['writable'],
        'known'      => $res['known'],
        'privileges' => $res['privileges'],
        'message'    => $res['writable']
            ? 'This account may write to that address book, so changes made in FreeITSM can be sent back to it.'
            : 'This account can read that address book but not write to it. Changes made in FreeITSM will stay in '
              . 'FreeITSM until the account is given write permission on the server.',
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
