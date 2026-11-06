<?php
/**
 * API Endpoint: acknowledge (or un-acknowledge) a mailbox health warning.
 *
 * "Not assigned to a company", "no ticket origin", "no outgoing server" and the
 * rest can all be somebody's deliberate choice. Without this, a correctly
 * configured mailbox would carry a warning mark for ever — and a mark that never
 * clears is one people stop reading, which would cost us the marks that matter.
 *
 * Only WARNINGS can be dismissed. Errors — reading the wrong inbox, credentials
 * that no longer decrypt — are faults rather than choices, and are refused here
 * as well as in the UI, so a hand-made request can't silence one.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/encryption.php';
require_once '../../includes/mailbox_graph.php';
require_once '../../includes/mailbox_health.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');
requireCapabilityJson(Cap::TICKETS_MAILBOXES);

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid request data']);
    exit;
}

$mailboxId = (int) ($data['mailbox_id'] ?? 0);
$key       = trim((string) ($data['key'] ?? ''));
$dismiss   = !empty($data['dismissed']);

if ($mailboxId <= 0 || $key === '') {
    echo json_encode(['success' => false, 'error' => 'A mailbox and a warning are required']);
    exit;
}

try {
    $conn = connectToDatabase();

    $stmt = $conn->prepare("SELECT * FROM target_mailboxes WHERE id = ?");
    $stmt->execute([$mailboxId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        echo json_encode(['success' => false, 'error' => 'Mailbox not found']);
        exit;
    }

    // Recompute this mailbox's problems and check the key is one that is actually
    // present AND dismissible. Dismissing something that isn't wrong would let a
    // future genuine warning arrive pre-silenced.
    if ($dismiss) {
        try { $row = decryptMailboxRow($row); } catch (Exception $e) { /* health still works */ }
        $row['is_active']    = (bool) $row['is_active'];
        $row['auth_status']  = ''; // not needed to validate dismissibility below
        $originNames = [];
        foreach ($conn->query("SELECT id, name FROM ticket_origins") as $o) {
            $originNames[(int)$o['id']] = $o['name'];
        }
        $allowed = [];
        foreach (mailboxHealthProblems($row, ['origin_names' => $originNames]) as $p) {
            if (!empty($p['dismissible'])) $allowed[$p['key']] = true;
        }
        if (!isset($allowed[$key])) {
            echo json_encode(['success' => false, 'error' => 'That warning cannot be dismissed']);
            exit;
        }
    }

    $current = json_decode((string) ($row['health_dismissed'] ?? ''), true);
    if (!is_array($current)) $current = [];
    $current = array_values(array_unique(array_map('strval', $current)));

    if ($dismiss) {
        if (!in_array($key, $current, true)) $current[] = $key;
    } else {
        $current = array_values(array_filter($current, static fn($k) => $k !== $key));
    }

    $upd = $conn->prepare("UPDATE target_mailboxes SET health_dismissed = ? WHERE id = ?");
    $upd->execute([$current ? json_encode($current) : null, $mailboxId]);

    echo json_encode(['success' => true, 'dismissed' => $current]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
