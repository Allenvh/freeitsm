<?php
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/encryption.php';
require_once '../../includes/mailbox_schema.php';
require_once '../../includes/mail/MailboxProviderFactory.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

try {
    $conn = connectToDatabase();
    ensureTargetMailboxSchema($conn);

    $id = (int)($_GET['mailbox_id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Mailbox ID is required']);
        exit;
    }

    $st = $conn->prepare('SELECT * FROM target_mailboxes WHERE id = ?');
    $st->execute([$id]);
    $mb = decryptMailboxRow($st->fetch(PDO::FETCH_ASSOC));
    if (!$mb) {
        echo json_encode(['success' => false, 'error' => 'Mailbox not found']);
        exit;
    }

    MailboxProviderFactory::imap($mb)->test();

    $connectedAs = $mb['imap_username'] ?: ($mb['target_mailbox'] ?? 'IMAP/SMTP');
    $addresses = json_encode([$connectedAs]);
    $update = $conn->prepare("UPDATE target_mailboxes
        SET provider = 'imap_smtp', provider_type = 'imap_smtp', auth_mode = 'basic',
            authenticated_as = ?, authenticated_addresses = ?
        WHERE id = ?");
    $update->execute([$connectedAs, $addresses, $id]);

    error_log('audit: mailbox_connection_test type=imap mailbox_id=' . $id . ' status=connected');
    echo json_encode(['success' => true, 'status' => 'connected']);
} catch (Exception $e) {
    error_log('audit: mailbox_connection_test_failed type=imap error=' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
