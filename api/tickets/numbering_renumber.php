<?php
/**
 * API: renumber existing tickets into the current scheme — preview or live.
 *
 * 🔴 THIS IS A MIGRATION TOOL, NOT HOUSEKEEPING. It rewrites the reference on
 * every ticket, and those references are quoted in emails, change records,
 * knowledge articles and customers' own spreadsheets.
 *
 * All of the thinking lives in TicketNumbering::planRenumber() and
 * ::applyRenumber() so that it can be tested — see tests/ticket-numbering.php.
 * This file is only the door: authenticate, plan, and either describe the plan
 * or carry it out. The preview and the live run share ONE code path, so what
 * somebody is shown is exactly what would happen.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/ticket_numbering.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');
requireCapabilityJson(Cap::TICKETS_NUMBERING);

try {
    $conn = connectToDatabase();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $live = (($data['mode'] ?? 'preview') === 'live');

    // The SAVED settings, not anything posted — a renumber must do what the
    // screen says it will do, and the screen shows what was saved.
    TicketNumbering::forget();
    $plan = TicketNumbering::planRenumber($conn, TicketNumbering::settings($conn));

    if ($live) {
        TicketNumbering::applyRenumber($conn, $plan);
    }

    echo json_encode([
        'success'    => true,
        'mode'       => $live ? 'live' : 'preview',
        'total'      => $plan['total'],
        'changing'   => $plan['changing'],
        'skipped'    => $plan['skipped'],
        // First and last few — the shape of the run, not 600 rows.
        'first'      => array_slice($plan['planned'], 0, 5),
        'last'       => array_slice($plan['planned'], -5),
        'next_after' => $plan['next_after'],
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
