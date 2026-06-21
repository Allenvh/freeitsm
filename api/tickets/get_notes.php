<?php
/**
 * API Endpoint: Get notes for a ticket
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

$ticket_id = $_GET['ticket_id'] ?? null;

if (!$ticket_id) {
    echo json_encode(['success' => false, 'error' => 'Ticket ID required']);
    exit;
}

try {
    $conn = connectToDatabase();

    // Multi-tenancy: don't reveal a ticket in a company this analyst can't access.
    if (!analystCanAccessTicket($conn, (int)$_SESSION['analyst_id'], $ticket_id)) {
        echo json_encode(['success' => false, 'error' => 'Ticket not found']);
        exit;
    }

    $sql = "SELECT
                n.id,
                n.ticket_id,
                n.analyst_id,
                n.note_text,
                n.is_internal,
                n.created_datetime,
                a.full_name as analyst_name
            FROM ticket_notes n
            JOIN analysts a ON n.analyst_id = a.id
            WHERE n.ticket_id = ?
            ORDER BY n.created_datetime DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$ticket_id]);
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($notes as &$note) {
        $note['is_internal'] = (bool)$note['is_internal'];
        if ($note['created_datetime']) {
            $note['created_datetime'] = date('Y-m-d\TH:i:s', strtotime($note['created_datetime']));
        }
    }

    echo json_encode([
        'success' => true,
        'notes' => $notes
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>
