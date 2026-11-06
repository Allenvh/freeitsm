<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Charging assets, contracts and people to a cost centre (GH #160, stage 2).
 *
 *   php tests/cost-centre-assign.php
 *
 * The rules in includes/cost_centre_assign.php, through every path that writes
 * one: the asset service (screen + REST), asset create, the asset import's
 * code lookup, the contracts service (by id, by code, the old free text, moving
 * company), and the delete refusal. Every "refused" has a positive control.
 *
 * Runs on the real database inside a transaction that is ALWAYS rolled back:
 * cost centres, assets and contracts are made for the run and none survive it.
 * People are saved through an endpoint in another process, so they are covered
 * by the HTTP check in the release notes' test list rather than here.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/tenancy.php';
require_once __DIR__ . '/../includes/cost_centre_assign.php';
require_once __DIR__ . '/../includes/services/assets.php';
require_once __DIR__ . '/../includes/services/asset_import.php';
require_once __DIR__ . '/../includes/services/contracts.php';
require_once __DIR__ . '/../includes/services/cost_centres.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}
function err(callable $fn): ?string {
    try { $fn(); return null; } catch (ServiceError $e) { return $e->kind . ': ' . $e->getMessage(); }
}

echo "\nCharging things to a cost centre (GH #160, stage 2)\n" . str_repeat('=', 70) . "\n";
$conn = connectToDatabase();
foreach (['assets', 'contracts', 'users'] as $t) {
    if (!costCentreReady($conn, $t)) { echo "  SKIP  run Database Verification first ($t)\n"; exit(0); }
}
$default = getDefaultTenantId($conn);
$other = (int)$conn->query("SELECT id FROM tenants WHERE id <> $default AND is_active = 1 ORDER BY id LIMIT 1")->fetchColumn();
if (!$other) { echo "  SKIP  needs a second company\n"; exit(0); }
$admin = new ActorContext(actorId: 1, companyScope: null, source: 'ui');

$conn->beginTransaction();
try {
    $mk = function (int $tenant, string $code, int $active = 1) use ($conn): int {
        $conn->prepare("INSERT INTO cost_centres (tenant_id, code, name, is_active) VALUES (?, ?, ?, ?)")
             ->execute([$tenant, $code, 'Test ' . $code, $active]);
        return (int)$conn->lastInsertId();
    };
    $ccA    = $mk($default, 'ZZ160-A');
    $ccB    = $mk($default, 'ZZ160-B');
    $ccOff  = $mk($default, 'ZZ160-OFF', 0);
    $ccElse = $mk($other, 'ZZ160-X');

    echo "\nThe picker list:\n";
    $ids = array_column(costCentreOptions($conn, $default), 'id');
    ok('offers its company\'s active ones', in_array($ccA, $ids, true) && in_array($ccB, $ids, true));
    ok('...not an inactive one', !in_array($ccOff, $ids, true));
    ok('...nor another company\'s', !in_array($ccElse, $ids, true));
    ok('...but keeps the one a record holds, even inactive (POSITIVE CONTROL)', in_array($ccOff, array_column(costCentreOptions($conn, $default, $ccOff), 'id'), true));
    ok('...and never keeps one from another company', !in_array($ccElse, array_column(costCentreOptions($conn, $default, $ccElse), 'id'), true));

    echo "\nAssets:\n";
    $asset = AssetsService::createAsset($conn, $admin, ['hostname' => 'ZZ160-' . bin2hex(random_bytes(3)), 'cost_centre_id' => $ccA], 'test', $default);
    ok('created already charged to an active cost centre (POSITIVE CONTROL)', (int)$conn->query("SELECT cost_centre_id FROM assets WHERE id = $asset")->fetchColumn() === $ccA);
    ok('create refuses another company\'s', ($e = err(fn() => AssetsService::createAsset($conn, $admin, ['hostname' => 'ZZ160-x' . bin2hex(random_bytes(3)), 'cost_centre_id' => $ccElse], 'test', $default))) !== null && str_starts_with($e, 'validation'), (string)$e);
    ok('create refuses an inactive one', ($e = err(fn() => AssetsService::createAsset($conn, $admin, ['hostname' => 'ZZ160-y' . bin2hex(random_bytes(3)), 'cost_centre_id' => $ccOff], 'test', $default))) !== null && stripos($e, 'inactive') !== false, (string)$e);
    ok('update to another active one (POSITIVE CONTROL)', err(fn() => AssetsService::updateFields($conn, $admin, $asset, ['cost_centre_id' => $ccB])) === null);
    ok('update refuses another company\'s', err(fn() => AssetsService::updateFields($conn, $admin, $asset, ['cost_centre_id' => $ccElse])) !== null);
    ok('update refuses an inactive one', err(fn() => AssetsService::updateFields($conn, $admin, $asset, ['cost_centre_id' => $ccOff])) !== null);
    $conn->exec("UPDATE cost_centres SET is_active = 0 WHERE id = $ccB");
    ok('an asset KEEPS one that was switched off after it was given', err(fn() => AssetsService::updateFields($conn, $admin, $asset, ['cost_centre_id' => $ccB, 'model' => 'Kept'])) === null
       && (int)$conn->query("SELECT cost_centre_id FROM assets WHERE id = $asset")->fetchColumn() === $ccB);
    $conn->exec("UPDATE cost_centres SET is_active = 1 WHERE id = $ccB");
    ok('cleared with none', err(fn() => AssetsService::updateFields($conn, $admin, $asset, ['cost_centre_id' => ''])) === null
       && $conn->query("SELECT cost_centre_id FROM assets WHERE id = $asset")->fetchColumn() === null);
    $hist = $conn->query("SELECT COUNT(*) FROM asset_history WHERE asset_id = $asset AND field_name = 'cost_centre'")->fetchColumn();
    ok('changes are in the asset history', (int)$hist >= 2, (string)$hist);

    echo "\nAsset import (by CODE, never as an id):\n";
    $resolve = new ReflectionMethod('AssetImportService', 'resolveLookups');
    $resolve->setAccessible(true);
    $core = $resolve->invoke(null, $conn, ['cost_centre_id' => 'zz160-a'], $default);
    ok('a code (any case) becomes its id (POSITIVE CONTROL)', ($core['cost_centre_id'] ?? null) === $ccA, json_encode($core));
    $e = null; try { $resolve->invoke(null, $conn, ['cost_centre_id' => 'NOPE-160'], $default); } catch (ServiceError $x) { $e = $x->getMessage(); }
    ok('an unknown code is refused with the code named', $e !== null && str_contains($e, 'NOPE-160'), (string)$e);
    $e = null; try { $resolve->invoke(null, $conn, ['cost_centre_id' => 'ZZ160-X'], $default); } catch (ServiceError $x) { $e = $x->getMessage(); }
    ok('another company\'s code is unknown here', $e !== null, (string)$e);
    $e = null; try { $resolve->invoke(null, $conn, ['cost_centre_id' => (string)$ccA], $default); } catch (ServiceError $x) { $e = $x->getMessage(); }
    ok('a number is read as a CODE, not an id', $e !== null, 'id ' . $ccA . ' was accepted as an id');

    echo "\nContracts:\n";
    $num = fn() => 'ZZ160-' . bin2hex(random_bytes(3));
    $c1 = ContractsService::saveContract($conn, $admin, ['contract_number' => $num(), 'title' => 'cc test', 'tenant_id' => $default, 'cost_centre_id' => $ccA])['id'];
    ok('saved with an active cost centre (POSITIVE CONTROL)', (int)$conn->query("SELECT cost_centre_id FROM contracts WHERE id = $c1")->fetchColumn() === $ccA);
    ok('refuses another company\'s', err(fn() => ContractsService::saveContract($conn, $admin, ['id' => $c1, 'cost_centre_id' => $ccElse])) !== null);
    ok('by CODE, as the API always took it (POSITIVE CONTROL)', err(fn() => ContractsService::saveContract($conn, $admin, ['id' => $c1, 'cost_centre' => 'ZZ160-B'])) === null
       && (int)$conn->query("SELECT cost_centre_id FROM contracts WHERE id = $c1")->fetchColumn() === $ccB);
    ok('an unknown code is refused', ($e = err(fn() => ContractsService::saveContract($conn, $admin, ['id' => $c1, 'cost_centre' => 'NOPE-160']))) !== null && stripos($e, 'NOPE-160') !== false, (string)$e);

    $c2 = ContractsService::saveContract($conn, $admin, ['contract_number' => $num(), 'title' => 'legacy', 'tenant_id' => $default])['id'];
    $conn->exec("UPDATE contracts SET cost_centre = 'Old typed text' WHERE id = $c2");
    ContractsService::saveContract($conn, $admin, ['id' => $c2, 'title' => 'legacy renamed', 'cost_centre_id' => null]);
    ok('the old free text is KEPT while no cost centre is chosen', $conn->query("SELECT cost_centre FROM contracts WHERE id = $c2")->fetchColumn() === 'Old typed text');
    ContractsService::saveContract($conn, $admin, ['id' => $c2, 'cost_centre_id' => $ccA]);
    $row = $conn->query("SELECT cost_centre, cost_centre_id FROM contracts WHERE id = $c2")->fetch(PDO::FETCH_ASSOC);
    ok('...and cleared once one is', $row['cost_centre'] === null && (int)$row['cost_centre_id'] === $ccA, json_encode($row));

    ok('moving company with the old company\'s cost centre is refused',
       ($e = err(fn() => ContractsService::saveContract($conn, $admin, ['id' => $c1, 'tenant_id' => $other]))) !== null && stripos($e, 'cost centre') !== false, (string)$e);
    ok('POSITIVE CONTROL: moving with one of the new company\'s is fine',
       err(fn() => ContractsService::saveContract($conn, $admin, ['id' => $c1, 'tenant_id' => $other, 'cost_centre_id' => $ccElse])) === null);

    echo "\nDeleting a cost centre in use:\n";
    $used = costCentreUsage($conn, $ccA);
    ok('its uses are counted', ($used['contract(s)'] ?? 0) >= 1, json_encode($used));
    ok('deleting it is refused, saying what uses it',
       ($e = err(fn() => CostCentresService::delete($conn, $admin, $ccA))) !== null && str_starts_with($e, 'conflict') && stripos($e, 'contract') !== false, (string)$e);
    $free = $mk($default, 'ZZ160-FREE');
    ok('POSITIVE CONTROL: an unused one deletes', err(fn() => CostCentresService::delete($conn, $admin, $free)) === null);
} catch (Throwable $e) {
    ok('the run completed', false, get_class($e) . ': ' . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine());
} finally {
    if ($conn->inTransaction()) $conn->rollBack();
}
$left = (int)$conn->query("SELECT COUNT(*) FROM cost_centres WHERE code LIKE 'ZZ160-%'")->fetchColumn()
      + (int)$conn->query("SELECT COUNT(*) FROM assets WHERE hostname LIKE 'ZZ160-%'")->fetchColumn()
      + (int)$conn->query("SELECT COUNT(*) FROM contracts WHERE contract_number LIKE 'ZZ160-%'")->fetchColumn();
ok('nothing survived the run (rolled back)', $left === 0, "{$left} rows left");

echo "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
