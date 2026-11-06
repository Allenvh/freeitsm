<?php
/**
 * API: Get self-service user profile
 *
 * GET - display name, email, preferred name, and the contact details a person
 * may maintain about themselves: the ones an administrator has allowed on
 * System → Portal profile (portalProfileEditableFields()), which ones a
 * directory owns, and whether a change is sent to an address book.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/users.php';   // portalProfileAccess()

header('Content-Type: application/json');

if (!isset($_SESSION['ss_user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

try {
    $conn = connectToDatabase();

    $stmt = $conn->prepare("SELECT email, display_name, preferred_name FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['ss_user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $access = $user ? portalProfileAccess($conn, (int)$_SESSION['ss_user_id']) : null;

    // ⚠️ A missing row must not be reported as a person with every field blank.
    // The session outlives the record if an administrator deletes the account
    // mid-session, and rendering that as an empty, editable form would invite
    // the user to Save it — writing their guesses onto nothing, or onto a
    // recreated id. Say it plainly instead.
    if (!$user || !$access) {
        echo json_encode(['success' => false, 'error' => 'Account not found']);
        exit;
    }

    $out = [
        'success'        => true,
        'email'          => $user['email'],
        'display_name'   => $user['display_name'],
        'preferred_name' => $user['preferred_name'] ?? '',
        // Kept for anything still reading it: true when a directory owns ANY
        // field that is offered. The form itself uses `locked`.
        'is_managed'     => (bool)$access['locked'],
        // The names themselves, so the page can build its own form from the
        // server's list instead of carrying a second copy that can drift. A
        // field the administrator switched off is simply not in it.
        'fields'         => $access['fields'],
        'locked'         => $access['locked'],
        // Their changes go to their organisation's address book too - said on
        // the form, because it is somebody else's system being written to.
        'address_book'   => $access['address_book'],
    ];
    foreach ($access['fields'] as $f) {
        $out[$f] = $access['row'][$f] ?? '';
    }
    echo json_encode($out);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
