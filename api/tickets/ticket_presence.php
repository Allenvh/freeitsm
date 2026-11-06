<?php
/**
 * API: heartbeat + read, in one request.
 *
 * POST { ticket_id, composing?, leave? } -> { success, others: [...], stale_seconds }
 *
 * One endpoint does both halves deliberately: every client that wants to know
 * who else is here is, by definition, here itself — so splitting them would
 * double the request rate of the busiest poll in the product for no gain.
 *
 * Module access only, and then `analystCanAccessTicket()`. Presence leaks the
 * NAMES of colleagues against a ticket reference, so the company scope has to
 * hold here exactly as it does on the ticket itself.
 *
 * `leave` is fire-and-forget: it rides on navigator.sendBeacon during page
 * teardown, when the browser will not wait for a response and cannot report a
 * failure. Nothing depends on it arriving — a missed leave just means the
 * indicator fades on the stale window instead of instantly.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/ticket_presence.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('tickets');

try {
    $raw  = file_get_contents('php://input');
    $data = json_decode($raw, true) ?: [];

    $ticketId  = (int)($data['ticket_id'] ?? 0);
    $composing = !empty($data['composing']);
    $leaving   = !empty($data['leave']);
    $analystId = (int)$_SESSION['analyst_id'];

    $conn = connectToDatabase();

    // Leaving everything (the browser is going away) names no ticket, so it
    // needs no per-ticket access check — it only ever deletes your own rows.
    if ($leaving && $ticketId <= 0) {
        presenceLeave($conn, 0, $analystId);
        echo json_encode(['success' => true, 'others' => []]);
        exit;
    }

    if ($ticketId <= 0) throw new Exception('ticket_id is required');

    if (!analystCanAccessTicket($conn, $analystId, $ticketId)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Ticket not found']);
        exit;
    }

    if ($leaving) {
        presenceLeave($conn, $ticketId, $analystId);
        echo json_encode(['success' => true, 'others' => []]);
        exit;
    }

    presenceHeartbeat($conn, $ticketId, $analystId, $composing);
    presencePurge($conn);

    echo json_encode([
        'success'       => true,
        'others'        => presenceOthers($conn, $ticketId, $analystId),
        'stale_seconds' => PRESENCE_STALE_SECONDS,
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
