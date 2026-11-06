<?php
/**
 * Assets -> Leases -> Due back (3.5.0): every asset still out on lease whose
 * end date falls within the next ?days= (0 = everything still out; overdue ones
 * always included), in the active company or all of them. ?lease_id= narrows
 * it to one lease. ?format=csv downloads it instead.
 */
require __DIR__ . '/_boot.php';
requireModuleAccessJson('assets');   // also inside leaseEndpoint(); here so the endpoint audit (D005) can see it

if (($_GET['format'] ?? '') === 'csv') {
    require_once __DIR__ . '/../../../includes/spreadsheet.php';
    $conn = connectToDatabase();
    $rows = leaseReady($conn) ? leaseDueBack($conn, (int)$_SESSION['analyst_id'], max(0, (int)($_GET['days'] ?? 90)), (int)($_GET['lease_id'] ?? 0)) : [];
    $out = [];
    foreach ($rows as $r) {
        $out[] = [$r['hostname'], $r['asset_tag'], $r['service_tag'], trim($r['manufacturer'] . ' ' . $r['model']), $r['held_by'], $r['location_name'],
                  $r['lease_name'], $r['reference'], $r['schedule_name'], $r['supplier_name'], $r['company_name'],
                  $r['start_date'], $r['end_date'], $r['notice_date'], $r['outcome_name']];
    }
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="leases-due-back-' . date('Y-m-d') . '.csv"');
    echo spreadsheetWriteCsv(['Hostname', 'Asset tag', 'Service tag', 'Model', 'Held by', 'Location', 'Lease', 'Agreement',
                              'Schedule', 'Supplier', 'Company', 'Lease starts', 'Lease ends', 'Notice by', 'Outcome'], $out);
    exit;
}

leaseEndpoint('GET', function (PDO $conn, ActorContext $ctx, array $in) {
    return ['assets' => leaseDueBack($conn, $ctx->actorId, max(0, (int)($in['days'] ?? 90)), (int)($in['lease_id'] ?? 0))];
});
