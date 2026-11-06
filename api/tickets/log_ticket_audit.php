<?php
/**
 * API Endpoint: Log ticket audit entry
 * Records changes to ticket properties
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

// Writes a ticket audit entry from the inbox — everyday work. It had NO module check.
requireModuleAccessJson('tickets');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

$ticketId = $data['ticket_id'] ?? null;
$fieldName = $data['field_name'] ?? null;
$oldValue = $data['old_value'] ?? null;
$newValue = $data['new_value'] ?? null;
$analystId = $_SESSION['analyst_id'];

if (!$ticketId || !$fieldName) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $conn = connectToDatabase();

    // Multi-tenancy: don't write audit against a ticket in a company this analyst can't access.
    if (!analystCanAccessTicket($conn, (int)$_SESSION['analyst_id'], $ticketId)) {
        echo json_encode(['success' => false, 'error' => 'Ticket not found']);
        exit;
    }

    // 🔴 History is not free text. This accepted any field_name and any values, and
    // SLA timing is built from the Status rows (includes/sla.php) - so an analyst
    // could pause a ticket's SLA clock with a made-up "Status -> On Hold" row while
    // the ticket stayed Open, and write any other line into the history a case
    // report prints. Reported privately, October 2026. Only the fields the inbox
    // logs (after its own successful save) are accepted...
    $allowedFields = ['Department', 'Status', 'Owner', 'Team', 'Ticket Type', 'Priority', 'Sensitivity',
                      'Origin', 'First Time Fix', 'IT Training', 'Category', 'Category at Close', 'Resolution Code'];
    if (!in_array($fieldName, $allowedFields, true)) {
        echo json_encode(['success' => false, 'error' => 'That field is not recorded here']);
        exit;
    }
    // ...and a Status row must say what the ticket's status really now is, and
    // be a change from the last one recorded - so it can only ever record a real
    // transition, never invent one.
    if ($fieldName === 'Status') {
        $cur = $conn->prepare("SELECT s.name FROM tickets t JOIN ticket_statuses s ON s.id = t.status_id WHERE t.id = ?");
        $cur->execute([(int)$ticketId]);
        $last = $conn->prepare("SELECT new_value FROM ticket_audit WHERE ticket_id = ? AND field_name = 'Status' ORDER BY id DESC LIMIT 1");
        $last->execute([(int)$ticketId]);
        // Case-insensitive, as the status lookup in assign_ticket.php is.
        $now = strtolower(trim((string)$cur->fetchColumn()));
        $prev = $last->fetchColumn();
        if ($now === '' || $now !== strtolower(trim((string)$newValue))
            || ($prev !== false && strtolower(trim((string)$prev)) === $now)) {
            echo json_encode(['success' => false, 'error' => 'That status change does not match the ticket']);
            exit;
        }
    }

    $sql = "INSERT INTO ticket_audit (ticket_id, analyst_id, field_name, old_value, new_value, created_datetime)
            VALUES (?, ?, ?, ?, ?, UTC_TIMESTAMP())";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$ticketId, $analystId, $fieldName, $oldValue, $newValue]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

?>
