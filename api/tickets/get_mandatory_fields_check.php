<?php
/**
 * API Endpoint: which mandatory fields would be empty if these tickets closed now.
 *
 * GET ?ticket_id=N
 *   -> { success, mode: "warn"|"notify"|"block", record: bool,
 *        missing: [ { key, label } ],
 *        tickets: [ { ticket_id, ticket_number, mode, record, missing } ] }
 *
 * GET ?ticket_ids=1,2,3        (bulk actions; at most MAX_IDS)
 *   -> { success, tickets: [ ... ] }   only the tickets with something missing
 *
 * Asked by the inbox the moment an analyst closes a ticket - from the status
 * dropdown, the right-click menu, a drag onto a closed status or a bulk action -
 * so it can say "close anyway?" or "fill these in first" BEFORE sending the
 * close. The server enforces the rule again on the close itself
 * (TicketsService::updateTicket), so this is only the conversation - never the
 * control. If it fails, the inbox says nothing and lets the server decide,
 * rather than turning a failed question into a refusal nobody can clear.
 *
 * mode and record are reported per ticket because they resolve per company.
 * A ticket that is out of the analyst's scope is simply left out.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/i18n.php';
require_once '../../includes/services/tickets.php';

header('Content-Type: application/json');

const MAX_IDS = 500;

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');
I18n::initFromSession();

$single = isset($_GET['ticket_id']);
$ids = $single
    ? [(int) $_GET['ticket_id']]
    : array_map('intval', explode(',', (string) ($_GET['ticket_ids'] ?? '')));
$ids = array_values(array_unique(array_filter($ids, fn($i) => $i > 0)));
if (!$ids) {
    echo json_encode(['success' => false, 'error' => 'ticket_id is required']);
    exit;
}
if (count($ids) > MAX_IDS) {
    echo json_encode(['success' => false, 'error' => 'At most ' . MAX_IDS . ' tickets per request']);
    exit;
}

try {
    $conn = connectToDatabase();
    $ctx  = ActorContext::fromSession($conn);

    $tickets = [];
    foreach ($ids as $id) {
        try {
            // The same scope rule as every other ticket read.
            $ticket = TicketsService::loadTicket($conn, $ctx, $id);
        } catch (ServiceError $e) {
            if ($single) throw $e;
            continue;
        }
        $tenantId = $ticket['tenant_id'] !== null ? (int) $ticket['tenant_id'] : null;
        $cfg      = MandatoryFieldsService::settings($conn, $tenantId);

        $missing = [];
        foreach (MandatoryFieldsService::missing($conn, $tenantId, $ticket) as $key => $english) {
            $label = t('tickets.settings.mandatory.fields.' . $key);
            $missing[] = [
                'key'   => $key,
                // t() hands the key back when a string is absent; never show that.
                'label' => ($label === '' || $label === 'tickets.settings.mandatory.fields.' . $key) ? $english : $label,
            ];
        }
        if (!$single && !$missing) continue;

        $tickets[] = [
            'ticket_id'     => $id,
            'ticket_number' => (string) ($ticket['ticket_number'] ?? ''),
            'mode'          => $cfg['mode'],
            'record'        => $cfg['record'],
            'missing'       => $missing,
        ];
    }

    $out = ['success' => true, 'tickets' => $tickets];
    if ($single) {
        // The single-ticket shape the reading pane has always read.
        $out['mode']    = $tickets[0]['mode'];
        $out['record']  = $tickets[0]['record'];
        $out['missing'] = $tickets[0]['missing'];
    }
    echo json_encode($out);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
