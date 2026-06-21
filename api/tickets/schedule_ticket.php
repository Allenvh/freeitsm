<?php
/**
 * API Endpoint: Schedule work for a ticket
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['ticket_id'])) {
    echo json_encode(['success' => false, 'error' => 'Ticket ID required']);
    exit;
}

$ticketId = (int)$input['ticket_id'];
$workStart = isset($input['work_start_datetime']) ? $input['work_start_datetime'] : null;

try {
    $conn = connectToDatabase();

    // Multi-tenancy: don't schedule a ticket in a company this analyst can't access.
    if (!analystCanAccessTicket($conn, (int)$_SESSION['analyst_id'], $ticketId)) {
        echo json_encode(['success' => false, 'error' => 'Ticket not found']);
        exit;
    }

    if ($workStart === null) {
        // Clear the schedule
        $sql = "UPDATE tickets SET work_start_datetime = NULL, updated_datetime = UTC_TIMESTAMP() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$ticketId]);
    } else {
        // Set the schedule
        $sql = "UPDATE tickets SET work_start_datetime = ?, updated_datetime = UTC_TIMESTAMP() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$workStart, $ticketId]);
    }

    echo json_encode([
        'success' => true,
        'message' => $workStart ? 'Work scheduled' : 'Schedule cleared'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

?>
