<?php
/**
 * API Endpoint: begin signing a mailbox in with Microsoft or Google.
 * POST { mailbox_id } -> { success, state }
 *
 * Issues the OAuth `state` the browser carries to the provider and back, and
 * remembers it in this session: which mailbox, who asked, and until when. The
 * callbacks (auth/oauth_callback.php, auth/google_oauth_callback.php) accept a
 * code only with a state issued here, once, to the same signed-in analyst.
 *
 * 🔴 The state used to be made up in the browser with Math.random() and never
 * checked, and the callbacks needed no sign-in, so they would save tokens for a
 * mailbox from a request nobody here had started. Found while fixing a private
 * report, October 2026.
 */
session_start();   // writable: the state is stored in the session
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';

csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');
requireCapabilityJson(Cap::TICKETS_MAILBOXES);   // the same gate as saving a mailbox

/** How long a sign-in may take, provider consent screen included. */
const MAILBOX_OAUTH_STATE_TTL = 900;

$data = json_decode(file_get_contents('php://input'), true) ?: [];
$mailboxId = (int)($data['mailbox_id'] ?? 0);

try {
    $conn = connectToDatabase();
    $stmt = $conn->prepare("SELECT provider FROM target_mailboxes WHERE id = ?");
    $stmt->execute([$mailboxId]);
    $provider = $stmt->fetchColumn();
    if ($mailboxId <= 0 || $provider === false) {
        echo json_encode(['success' => false, 'error' => 'Mailbox not found']);
        exit;
    }

    // The callbacks read the mailbox id from the prefix, so the shape stays as it
    // was; the random part is what makes it unguessable and the session is what
    // makes it single-use.
    $prefix = (MailboxProvider::fromValue($provider) === MailboxProvider::Google ? 'google_mailbox_' : 'mailbox_') . $mailboxId . '_';
    $state  = $prefix . bin2hex(random_bytes(16));

    // Drop expired entries so an abandoned sign-in does not linger.
    $pending = array_filter((array)($_SESSION['mailbox_oauth_states'] ?? []),
                            fn($s) => (int)($s['expires'] ?? 0) > time());
    $pending[$state] = [
        'mailbox_id' => $mailboxId,
        'analyst_id' => (int)$_SESSION['analyst_id'],
        'expires'    => time() + MAILBOX_OAUTH_STATE_TTL,
    ];
    $_SESSION['mailbox_oauth_states'] = $pending;

    echo json_encode(['success' => true, 'state' => $state]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
