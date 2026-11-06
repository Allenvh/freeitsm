<?php
/**
 * API: put one or more tickets to sleep until a time.
 *
 * POST { ticket_id | ticket_ids[], preset, until_local?, reason? }
 *   -> { success, snoozed, snoozed_until, failed[] }
 *
 * Module access only, like assign / status / rename: deciding a ticket can't be
 * worked until Thursday is everyday desk work, not administration.
 *
 * The preset is resolved SERVER-SIDE (includes/ticket_snooze.php) in the
 * analyst's own timezone, so "tomorrow morning" means their morning and the
 * install's wake hour is applied in one place rather than in each caller.
 *
 * Bulk: several ids LOOP the same single-ticket path rather than issuing one
 * UPDATE ... WHERE id IN (...) — every ticket gets its own access check and its
 * own audit row, and one refusal doesn't take the others down with it.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/timezone.php';
require_once '../../includes/ticket_snooze.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('tickets');

try {
    $data   = json_decode(file_get_contents('php://input'), true) ?: [];
    $preset = trim((string)($data['preset'] ?? ''));
    $reason = trim((string)($data['reason'] ?? ''));

    $ids = [];
    if (isset($data['ticket_ids']) && is_array($data['ticket_ids'])) {
        foreach ($data['ticket_ids'] as $id) { $id = (int)$id; if ($id > 0) $ids[] = $id; }
    } elseif (isset($data['ticket_id'])) {
        $id = (int)$data['ticket_id'];
        if ($id > 0) $ids[] = $id;
    }
    $ids = array_values(array_unique($ids));
    if (!$ids) throw new Exception('ticket_id is required');

    $analystId = (int)$_SESSION['analyst_id'];
    $conn = connectToDatabase();

    // The analyst's display zone is what "tomorrow at 9" is measured in.
    Tz::init();
    $untilUtc = resolveSnoozeUntil($conn, $preset, $data['until_local'] ?? null, Tz::current());

    $done = [];
    $failed = [];
    foreach ($ids as $ticketId) {
        if (!analystCanAccessTicket($conn, $analystId, $ticketId)) {
            $failed[] = $ticketId;
            continue;
        }
        snoozeTicket($conn, $ticketId, $analystId, $untilUtc, $reason);
        $done[] = $ticketId;
    }

    if (!$done) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Ticket not found']);
        exit;
    }

    echo json_encode([
        'success'       => true,
        'snoozed'       => count($done),
        'ticket_ids'    => $done,
        'failed'        => $failed,
        'snoozed_until' => $untilUtc,
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
