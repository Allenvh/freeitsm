<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Leases (3.5.0) - Assets -> Leases.
 *
 *   php tests/leases.php
 *
 * The rules in includes/leases.php: company scope (another company's lease is
 * not found; its assets, contract and cost centre cannot be used), one active
 * lease per asset, the asset's own lease dates kept in step both ways (schedule
 * dates -> asset; asset panel -> the asset's own dates on the schedule), outcomes,
 * Due back, and the notice entries in the calendar. Every "refused" has a
 * positive control.
 *
 * Runs on the real database inside a transaction that is ALWAYS rolled back.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/tenancy.php';
require_once __DIR__ . '/../includes/leases.php';
require_once __DIR__ . '/../includes/services/assets.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}
function err(callable $fn): ?string {
    try { $fn(); return null; } catch (ServiceError $e) { return $e->kind . ': ' . $e->getMessage(); }
}

echo "\nLeases (3.5.0)\n" . str_repeat('=', 70) . "\n";
$conn = connectToDatabase();
if (!leaseReady($conn)) { echo "  SKIP  run Database Verification first\n"; exit(0); }
$default = getDefaultTenantId($conn);
$other = (int)$conn->query("SELECT id FROM tenants WHERE id <> $default AND is_active = 1 ORDER BY id LIMIT 1")->fetchColumn();
$multi = isMultiTenant($conn) && $other > 0;
$admin = new ActorContext(actorId: 1, companyScope: null, source: 'ui');
$_SESSION['active_tenant_id'] = $default;

$conn->beginTransaction();
try {
    $asset = function (?int $tenant, string $name) use ($conn): int {
        $conn->prepare("INSERT INTO assets (hostname, tenant_id) VALUES (?, ?)")->execute([$name, $tenant]);
        return (int)$conn->lastInsertId();
    };
    $dates = function (int $id) use ($conn): array {
        $s = $conn->prepare("SELECT lease_start, lease_expiry FROM assets WHERE id = ?");
        $s->execute([$id]);
        return $s->fetch(PDO::FETCH_NUM);
    };
    $a1 = $asset(null, 'ZZLEASE-PC1');
    $a2 = $asset(null, 'ZZLEASE-PC2');
    $a3 = $asset(null, 'ZZLEASE-MON1');

    echo "\n-- A lease and its first schedule\n";
    ok('a lease needs a name', str_contains((string)err(fn() => leaseSave($conn, $admin, ['name' => ' '])), 'needs a name'));
    $L = leaseSave($conn, $admin, ['name' => 'ZZ Lease', 'reference' => 'AGR-1', 'notice_days' => 90]);
    $g = leaseGet($conn, 1, $L);
    ok('a new lease starts with one schedule', count($g['schedules']) === 1);
    $S1 = $g['schedules'][0]['id'];
    ok('notice days must be a number', str_contains((string)err(fn() => leaseSave($conn, $admin, ['id' => $L, 'notice_days' => 'soon'])), 'Notice'));
    ok('schedule end before start is refused', str_contains((string)err(fn() => leaseScheduleSave($conn, $admin, ['id' => $S1, 'start_date' => '2026-05-01', 'end_date' => '2026-01-01'])), 'cannot end before'));
    ok('a bad currency is refused', str_contains((string)err(fn() => leaseScheduleSave($conn, $admin, ['id' => $S1, 'currency' => 'pounds'])), 'three-letter'));
    leaseScheduleSave($conn, $admin, ['id' => $S1, 'name' => 'PCs', 'start_date' => '2026-01-01', 'end_date' => '2029-01-01', 'cost' => '1,250.50', 'cost_period' => 'month', 'currency' => 'gbp']);
    $s = $conn->query("SELECT cost, currency FROM lease_schedules WHERE id = $S1")->fetch(PDO::FETCH_ASSOC);
    ok('cost and currency are stored cleanly', (float)$s['cost'] === 1250.5 && $s['currency'] === 'GBP', json_encode($s));
    ok('the last schedule cannot be deleted', str_contains((string)err(fn() => leaseScheduleDelete($conn, $admin, $S1)), 'at least one'));
    $S2 = leaseScheduleSave($conn, $admin, ['lease_id' => $L, 'name' => 'Monitors', 'start_date' => '2026-03-01', 'end_date' => '2028-03-01']);
    ok('a second schedule with its own dates', $S2 > 0);

    echo "\n-- Assets on a schedule take its dates\n";
    $r = leaseAssetsAdd($conn, $admin, $S1, [$a1, $a2]);
    ok('two PCs added', $r['added'] === 2, json_encode($r));
    ok('PC1 now has the schedule dates', $dates($a1) === ['2026-01-01', '2029-01-01'], json_encode($dates($a1)));
    $h = (int)$conn->query("SELECT COUNT(*) FROM asset_history WHERE asset_id = $a1 AND field_name IN ('lease_start','lease_expiry')")->fetchColumn();
    ok('and the change is in the asset history', $h === 2, "rows: $h");
    $r = leaseAssetsAdd($conn, $admin, $S2, [$a1]);
    ok('an asset already out on a lease is refused', isset($r['refused'][$a1]) && $r['added'] === 0, json_encode($r));
    $r = leaseAssetsAdd($conn, $admin, $S2, [$a3]);
    ok('(control) a free asset goes on the other schedule', $r['added'] === 1 && $dates($a3) === ['2026-03-01', '2028-03-01']);

    echo "\n-- Schedule dates move -> the assets follow\n";
    leaseScheduleSave($conn, $admin, ['id' => $S1, 'end_date' => '2029-06-30']);
    ok('both PCs follow the new end date', $dates($a1)[1] === '2029-06-30' && $dates($a2)[1] === '2029-06-30');

    echo "\n-- One asset kept longer (its own dates)\n";
    AssetsService::updateFields($conn, $admin, $a2, ['lease_expiry' => '2029-12-31']);
    $own = $conn->query("SELECT end_date FROM lease_assets WHERE asset_id = $a2")->fetchColumn();
    ok('editing the date on the asset becomes its own end date', $own === '2029-12-31', (string)$own);
    leaseScheduleSave($conn, $admin, ['id' => $S1, 'end_date' => '2029-09-30']);
    ok('a schedule change leaves its own date alone', $dates($a2)[1] === '2029-12-31' && $dates($a1)[1] === '2029-09-30', json_encode([$dates($a1), $dates($a2)]));
    AssetsService::updateFields($conn, $admin, $a2, ['lease_expiry' => '2029-09-30']);
    $own = $conn->query("SELECT end_date FROM lease_assets WHERE asset_id = $a2")->fetchColumn();
    ok('typing the schedule date back clears its own date', $own === null, var_export($own, true));
    $rowA2 = (int)$conn->query("SELECT id FROM lease_assets WHERE asset_id = $a2")->fetchColumn();
    leaseAssetUpdate($conn, $admin, $rowA2, ['end_date' => '2030-03-31']);
    ok('own date set on the lease reaches the asset', $dates($a2)[1] === '2030-03-31');
    ok('own end before the start is refused', str_contains((string)err(fn() => leaseAssetUpdate($conn, $admin, $rowA2, ['end_date' => '2025-01-01'])), 'cannot end before'));

    echo "\n-- Outcomes\n";
    $returned = (int)$conn->query("SELECT id FROM lease_outcomes WHERE kind = 'closed' ORDER BY display_order LIMIT 1")->fetchColumn();
    $rowA1 = (int)$conn->query("SELECT id FROM lease_assets WHERE asset_id = $a1")->fetchColumn();
    leaseAssetUpdate($conn, $admin, $rowA1, ['outcome_id' => $returned]);
    $od = $conn->query("SELECT outcome_date FROM lease_assets WHERE id = $rowA1")->fetchColumn();
    ok('a closed outcome stamps today', $od === date('Y-m-d'), (string)$od);
    leaseScheduleSave($conn, $admin, ['id' => $S1, 'end_date' => '2029-10-31']);
    ok('a returned asset keeps its dates when the schedule moves', $dates($a1)[1] === '2029-09-30', json_encode($dates($a1)));
    $r = leaseAssetsAdd($conn, $admin, $S2, [$a1]);
    ok('a returned asset can go on another lease', $r['added'] === 1, json_encode($r));
    ok('putting the returned row back on lease is refused while it is out elsewhere',
       str_contains((string)err(fn() => leaseAssetUpdate($conn, $admin, $rowA1, ['outcome_id' => leaseDefaultOutcomeId($conn)])), 'another lease'));

    echo "\n-- Due back\n";
    $due = leaseDueBack($conn, 1, 0, $L);
    $ids = array_column($due, 'asset_id');
    ok('Due back lists the assets still out', in_array($a2, $ids, true) && in_array($a3, $ids, true));
    ok('...and on the second schedule, PC1 again', count(array_filter($due, fn($d) => $d['asset_id'] === $a1)) === 1);
    $soon = leaseDueBack($conn, 1, 30, $L);
    ok('a 30-day window leaves out leases ending in 2028+', count($soon) === 0, count($soon) . ' rows');
    leaseAssetUpdate($conn, $admin, $rowA2, ['end_date' => date('Y-m-d', strtotime('-3 days'))]);
    $soon = leaseDueBack($conn, 1, 30, $L);
    ok('(control) an overdue one is always in the window', count($soon) === 1 && $soon[0]['overdue'] === true);
    leaseAssetUpdate($conn, $admin, $rowA2, ['end_date' => '']);

    echo "\n-- Calendar notice entries\n";
    $conn->prepare("REPLACE INTO system_settings (setting_key, setting_value) VALUES ('asset_lease_surface', 'both')")->execute();
    leaseSyncCalendar($conn);
    $n = $conn->query("SELECT title, DATE(start_datetime) d FROM calendar_events WHERE source = 'asset_lease_notice' AND title LIKE 'Lease notice: ZZ Lease%' ORDER BY d")->fetchAll(PDO::FETCH_ASSOC);
    ok('one notice entry per schedule with something out', count($n) === 2, json_encode($n));
    ok('dated end minus the notice period', ($n[0]['d'] ?? '') === date('Y-m-d', strtotime('2028-03-01 -90 days')), json_encode($n));
    $conn->prepare("REPLACE INTO system_settings (setting_key, setting_value) VALUES ('asset_lease_surface', 'dashboard')")->execute();
    leaseSyncCalendar($conn);
    ok('(control) switched off, they go', (int)$conn->query("SELECT COUNT(*) FROM calendar_events WHERE source = 'asset_lease_notice'")->fetchColumn() === 0);

    echo "\n-- History on the asset\n";
    $hist = leaseHistoryForAsset($conn, 1, $a1);
    ok('PC1 shows both leases, the current one first', count($hist) === 2 && $hist[0]['active'] === true && $hist[1]['active'] === false, json_encode($hist));

    if ($multi) {
        echo "\n-- Companies\n";
        $x1 = $asset($other, 'ZZLEASE-OTHER');
        $r = leaseAssetsAdd($conn, $admin, $S1, [$x1]);
        ok('another company\'s asset cannot go on the lease', isset($r['refused'][$x1]), json_encode($r));
        $LO = leaseSave($conn, $admin, ['name' => 'ZZ Other lease', 'company_id' => $other]);
        $SO = leaseGet($conn, 1, $LO)['schedules'][0]['id'];
        ok('(control) it can go on its own company\'s lease', leaseAssetsAdd($conn, $admin, $SO, [$x1])['added'] === 1);
        ok('a lease with assets cannot change company',
           str_contains((string)err(fn() => leaseSave($conn, $admin, ['id' => $L, 'company_id' => $other])), 'cannot move'));
        ok('an asset on lease cannot move company',
           str_contains((string)err(fn() => AssetsService::moveToCompany($conn, $admin, $a3, $other)), 'on lease'));

        // An analyst who can only reach the default company.
        $conn->prepare("INSERT INTO analysts (username, full_name, email, password_hash, can_access_all_tenants) VALUES ('zzlease', 'ZZ Lease', 'zzlease@example.invalid', 'x', 0)")->execute();
        $lim = (int)$conn->lastInsertId();
        $conn->prepare("INSERT INTO analyst_tenant_access (analyst_id, tenant_id) VALUES (?, ?)")->execute([$lim, $default]);
        $limCtx = new ActorContext(actorId: $lim, companyScope: [$default], source: 'ui');
        ok('another company\'s lease is not found', str_contains((string)err(fn() => leaseGet($conn, $lim, $LO)), 'not found'));
        ok('(control) their own company\'s lease is', leaseGet($conn, $lim, $L)['id'] === $L);
        ok('nor can its schedules be edited', str_contains((string)err(fn() => leaseScheduleSave($conn, $limCtx, ['id' => $SO, 'name' => 'x'])), 'not found'));
        ok('nor its assets touched', str_contains((string)err(fn() => leaseAssetRemove($conn, $limCtx, (int)$conn->query("SELECT id FROM lease_assets WHERE asset_id = $x1")->fetchColumn())), 'not found'));
        ok('the list leaves it out', !in_array($LO, array_column(leaseList($conn, $lim), 'id'), true) && in_array($L, array_column(leaseList($conn, $lim), 'id'), true));
        ok('a lease cannot be put in a company they cannot reach', str_contains((string)err(fn() => leaseSave($conn, $limCtx, ['name' => 'x', 'company_id' => $other])), 'access'));

        // Contracts and cost centres of another company.
        $conn->prepare("INSERT INTO contracts (contract_number, title, tenant_id) VALUES ('ZZL-1', 'ZZ other', ?)")->execute([$other]);
        $cOther = (int)$conn->lastInsertId();
        ok('another company\'s contract cannot be linked', str_contains((string)err(fn() => leaseSave($conn, $admin, ['id' => $L, 'contract_id' => $cOther])), 'another company'));
        ok('(control) it links to its own company\'s lease', leaseSave($conn, $admin, ['id' => $LO, 'contract_id' => $cOther]) === $LO);
        $conn->prepare("INSERT INTO cost_centres (tenant_id, code, name, is_active) VALUES (?, 'ZZL-CC', 'ZZ', 1)")->execute([$other]);
        $ccOther = (int)$conn->lastInsertId();
        ok('another company\'s cost centre is refused', err(fn() => leaseSave($conn, $admin, ['id' => $L, 'cost_centre_id' => $ccOther])) !== null);
        ok('(control) its own company\'s is taken', leaseSave($conn, $admin, ['id' => $LO, 'cost_centre_id' => $ccOther]) === $LO);
    } else {
        echo "  SKIP  company checks need a second company\n";
    }

    echo "\n-- Delete\n";
    leaseDelete($conn, $admin, $L);
    ok('deleting the lease takes its schedules and rows', (int)$conn->query("SELECT COUNT(*) FROM lease_schedules WHERE lease_id = $L")->fetchColumn() === 0);
    ok('...and the assets keep their dates', $dates($a3)[1] === '2028-03-01');
} catch (Throwable $e) {
    $fail++;
    echo "  FAIL  unexpected: " . get_class($e) . ': ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine() . "\n";
} finally {
    if ($conn->inTransaction()) $conn->rollBack();
}

echo str_repeat('-', 70) . "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
