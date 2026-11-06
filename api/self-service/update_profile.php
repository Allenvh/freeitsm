<?php
/**
 * API: Update self-service user profile
 *
 * POST - preferred name, plus the contact details a person may maintain about
 * themselves: the ones an administrator allows on System → Portal profile
 * (portalProfileEditableFields(), never wider than USER_SELF_EDITABLE_FIELDS).
 *
 * 🔴 THIS ENDPOINT IS REACHED BY A CUSTOMER, NOT AN ANALYST. It writes to
 * `users`, the same table the analyst screens and directory sync write, so the
 * field list is taken from the server and NEVER from the request body. A
 * portal user posting {"manager_id":…} or {"employee_id":…} has those keys
 * ignored, not honoured — see includes/users.php for why those three are out.
 *
 * 🔑 A contact from an address book, where the administrator allows it, has
 * their change SENT TO THE ADDRESS BOOK FIRST and saved here only once it was
 * accepted. The analyst screens do it the other way round (save, then report
 * the push), and that is right for them: an analyst is told, and can act. A
 * customer cannot, and a local save the address book refused would be reverted
 * by the next import - an edit that silently vanishes overnight, which is the
 * one outcome this feature exists to prevent.
 */
session_start();
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/users.php';   // portalProfileAccess(), userPersonFieldValue()

header('Content-Type: application/json');

if (!isset($_SESSION['ss_user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// ⚠️ `?? []` is load-bearing, not tidiness. json_decode returns NULL on a body
// that is absent or malformed, and the `?? ''` on the next line survives that
// while `array_key_exists($f, null)` below is a TypeError in PHP 8 — a 500 on
// an empty POST, in an endpoint a customer's browser reaches.
$input = json_decode(file_get_contents('php://input'), true) ?? [];
$preferredName = trim($input['preferred_name'] ?? '');

if (strlen($preferredName) > 100) {
    echo json_encode(['success' => false, 'error' => 'Name must be 100 characters or less']);
    exit;
}

try {
    $conn = connectToDatabase();
    $userId = (int)$_SESSION['ss_user_id'];

    // ⚠️ null means NO ROW, which is not the same as an unmanaged person. The
    // session can outlive the record. Without this the UPDATE below would match
    // nothing and still report success.
    $access = portalProfileAccess($conn, $userId);
    if ($access === null) {
        echo json_encode(['success' => false, 'error' => 'Account not found']);
        exit;
    }

    // 🔴 "Absent means don't touch", and preferred_name had to join that rule.
    //
    // It used to be written UNCONDITIONALLY, so a body that simply did not
    // mention it wrote NULL and silently cleared it. Harmless while the only
    // caller always sent it; a live bug the moment this endpoint accepted
    // partial payloads, because POST {"phone":"…"} then wiped the name the
    // person is addressed by in every email. Exactly the trap
    // api/tickets/save_user.php documents at length after it deleted an email
    // address the same way — found here by posting only the contact fields and
    // reading the row back, not from the response, which said success.
    $sets = [];
    $args = [];
    $nameSent = array_key_exists('preferred_name', $input);
    if ($nameSent) {
        $sets[] = 'preferred_name = ?';
        $args[] = $preferredName !== '' ? $preferredName : null;
    }

    // The contact details. Iterating the SERVER'S list rather than the request
    // body is the whole guard: an unexpected key in the payload can never become
    // a column name. Absent keys are left alone, so a caller sending only
    // preferred_name does not blank somebody's telephone number.
    $changed = [];   // field => new value, only where it actually differs
    foreach (USER_SELF_EDITABLE_FIELDS as $f) {
        if (!array_key_exists($f, $input)) continue;
        // Switched off by an administrator since the form was drawn. Refused
        // rather than ignored: a save that quietly drops part of what the
        // person typed is worse than one that says so.
        if (!in_array($f, $access['fields'], true)) {
            echo json_encode(['success' => false, 'error' => 'not_offered', 'field' => $f]);
            exit;
        }
        if (in_array($f, $access['locked'], true)) {
            echo json_encode(['success' => false, 'error' => 'managed']);
            exit;
        }
        $v = userPersonFieldValue($f, $input[$f]);
        // Lengths match the columns (VARCHAR(150) for job title and office,
        // VARCHAR(50) for the two numbers). Checked here rather than left to
        // MySQL, which in non-strict mode TRUNCATES silently — the user would
        // see a saved value quietly shorter than the one they typed.
        $max = in_array($f, ['phone', 'mobile'], true) ? 50 : 150;
        if ($v !== null && mb_strlen($v) > $max) {
            echo json_encode([
                'success' => false,
                'error'   => 'too_long',
                'field'   => $f,
                'max'     => $max,
            ]);
            exit;
        }
        $sets[] = "$f = ?";
        $args[] = $v;
        if (trim((string)($access['row'][$f] ?? '')) !== trim((string)$v)) {
            $changed[$f] = $v;
        }
    }

    // --- the address book first, when it is taking this person's changes ---
    //
    // Only what actually changed is sent: the form posts every field on every
    // save, and pushing unchanged values would fill the address book's write
    // log with "nothing to send" for each preferred-name edit.
    if ($access['address_book'] && $changed) {
        require_once '../../includes/carddav_write.php';
        $push = cardDavPushPersonChanges($conn, $userId, $changed, $access['row'], null, true);
        if (empty($push['attempted']) || empty($push['ok'])) {
            // Nothing saved here either - see the header. The detail goes to
            // the address book's write log for an administrator; the person
            // gets a sentence they can act on.
            echo json_encode([
                'success'  => false,
                'error'    => 'address_book',
                'conflict' => !empty($push['conflict']),
            ]);
            exit;
        }
    }

    // Nothing to write is a success, not an error: a save with no changed field
    // is a no-op, and an empty SET would be a syntax error.
    if ($sets) {
        $args[] = $userId;
        $conn->prepare("UPDATE users SET " . implode(', ', $sets) . " WHERE id = ?")->execute($args);
    }

    // Refresh the greeting only when the NAME was actually part of this save —
    // recomputing it on a contact-details-only save would fall into the `else`
    // branch and replace a perfectly good preferred name with the display name.
    if ($nameSent) {
        if ($preferredName !== '') {
            $_SESSION['ss_user_name'] = $preferredName;
        } else {
            // Fall back to display_name or email
            $userStmt = $conn->prepare("SELECT display_name, email FROM users WHERE id = ?");
            $userStmt->execute([$userId]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['ss_user_name'] = $user['display_name'] ?: $user['email'];
        }
    }

    echo json_encode([
        'success'      => true,
        'display_name' => $_SESSION['ss_user_name'],
        'address_book' => $access['address_book'] && (bool)$changed,
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
