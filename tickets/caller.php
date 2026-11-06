<?php
/**
 * The page 3CX opens when a known caller rings (the lookup's ContactUrl).
 *
 * GET ?user_id=<users.id>  →  the caller's ticket, chosen by the same rule a call
 * note uses (System → Integrations → 3CX: latest open / only one / none), else
 * their record on Tickets → Users. So the screen in front of the analyst is the
 * ticket the call will be noted on.
 *
 * An ordinary signed-in page: the address 3CX holds is no secret, and opening it
 * shows nothing the analyst could not open anyway - the person and the ticket
 * are checked against this analyst's companies, and the ticket page does its own
 * checks again when it loads.
 */
session_start(['read_and_close' => true]);
require_once '../config.php';
require_once '../includes/functions.php';
require_once '../includes/tenancy.php';
require_once '../includes/integrations/threecx.php';

requireModuleAccess('tickets');

$userId = (int)($_GET['user_id'] ?? 0);
$analystId = (int)$_SESSION['analyst_id'];
$conn = connectToDatabase();

$to = 'tickets/';
if ($userId > 0 && analystCanAccessUser($conn, $analystId, $userId)) {
    [$ticketId] = threecxTicketFor($conn, $userId, threecxSettings($conn)['ticket_rule']);
    $to = ($ticketId && analystCanAccessTicket($conn, $analystId, $ticketId))
        ? 'tickets/?ticket_id=' . $ticketId
        : 'tickets/users.php?user_id=' . $userId;
}
header('Location: ' . BASE_URL . $to);
