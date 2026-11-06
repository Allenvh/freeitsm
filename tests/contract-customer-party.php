<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Contracts that belong to a company (3.5.0), contracts with a CUSTOMER (#153),
 * and a domain's customer person.
 *
 * A contract BELONGS to one company (contracts.tenant_id, NULL = Default) and
 * an analyst sees it only when they can reach that company - anywhere. Who it is
 * WITH (a supplier, or a customer company / person) is set separately and no
 * longer decides who sees it. This file checks the rule where it is applied,
 * with positive controls so a check that hides EVERYTHING cannot pass as one
 * that hides the right thing.
 *
 * ⚠️ It needs a restriction to test, and on most installs nobody is restricted.
 * Everything runs inside a transaction that is ALWAYS rolled back: the analyst's
 * all-companies flag is cleared and one company granted, contracts and settings
 * are written, and none of it survives the run.
 *
 * Run:  php tests/contract-customer-party.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/i18n.php';
require_once __DIR__ . '/../includes/tenancy.php';
require_once __DIR__ . '/../includes/services/contracts.php';
require_once __DIR__ . '/../includes/services/domains.php';
require_once __DIR__ . '/../includes/contract_party.php';
require_once __DIR__ . '/../includes/record_preview.php';
require_once __DIR__ . '/../includes/documents.php';
require_once __DIR__ . '/../includes/domains/customer.php';
I18n::initFromSession();

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}
function serviceError(callable $fn): ?string {
    try { $fn(); return null; } catch (ServiceError $e) { return $e->kind . ': ' . $e->getMessage(); }
}

echo "\nContracts: owned by a company (3.5.0), with a customer (#153)\n" . str_repeat('=', 70) . "\n";

$conn = connectToDatabase();
if (!contractPartyReady($conn) || !contractTenantReady($conn) || !domainCustomerReady($conn)) { echo "  SKIP  run Database Verification first\n"; exit(0); }
if (!isMultiTenant($conn)) { echo "  SKIP  needs two or more companies\n"; exit(0); }

$tenants = array_map('intval', $conn->query("SELECT id FROM tenants WHERE is_active = 1 ORDER BY id")->fetchAll(PDO::FETCH_COLUMN));
$default = getDefaultTenantId($conn);
$others  = array_values(array_diff($tenants, [$default]));
if (count($others) < 2) { echo "  SKIP  needs two companies besides Default\n"; exit(0); }
[$mine, $theirs] = $others;


// ⚠️ Not an admin, and chosen BEFORE anything asks about their companies:
// getAccessibleTenantIds() caches per analyst for the life of the process.
// And one who can open Contracts, or the positive controls would fail for the wrong reason.
$r = 0;
foreach ($conn->query("SELECT id FROM analysts WHERE is_active = 1 AND (is_admin = 0 OR is_admin IS NULL) ORDER BY id")->fetchAll(PDO::FETCH_COLUMN) as $cand) {
    if (analystCanAccessModule($conn, (int)$cand, 'contracts')) { $r = (int)$cand; break; }
}
$admin = 1;
if (!$r) { echo "  SKIP  needs a non-admin analyst\n"; exit(0); }

$conn->beginTransaction();
try {
    $conn->prepare("UPDATE analysts SET can_access_all_tenants = 0 WHERE id = ?")->execute([$r]);
    $conn->prepare("DELETE FROM analyst_tenant_access WHERE analyst_id = ?")->execute([$r]);
    $conn->prepare("DELETE FROM analyst_teams WHERE analyst_id = ?")->execute([$r]);
    $conn->prepare("INSERT INTO analyst_tenant_access (analyst_id, tenant_id) VALUES (?, ?)")->execute([$r, $mine]);
    // A leftover of the setting retired in 3.5.0 must change nothing.
    $conn->prepare("DELETE FROM system_settings WHERE setting_key = 'contracts_customer_visibility'")->execute();
    $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES ('contracts_customer_visibility', 'all')")->execute();

    ok("analyst {$r} is limited to company {$mine}", getAccessibleTenantIds($conn, $r) === [$mine],
       json_encode(getAccessibleTenantIds($conn, $r)));

    $adminCtx = new ActorContext(actorId: $admin, companyScope: null, source: 'ui');
    $rCtx     = new ActorContext(actorId: $r, companyScope: [$mine], source: 'ui');

    // People to be customers: one in each company, one with no company (= Default).
    $mkUser = function (?int $tenant, string $name) use ($conn): int {
        $conn->prepare("INSERT INTO users (email, display_name, tenant_id, is_active) VALUES (?, ?, ?, 1)")
             ->execute([strtolower(str_replace(' ', '.', $name)) . '.153test@example.invalid', $name, $tenant]);
        return (int)$conn->lastInsertId();
    };
    $uMine   = $mkUser($mine, 'Test Mine Person');
    $uTheirs = $mkUser($theirs, 'Test Theirs Person');
    $uNone   = $mkUser(null, 'Test Default Person');
    $mkAsset = function (int $tenant) use ($conn, $default): int {
        $conn->prepare("INSERT INTO assets (hostname, tenant_id) VALUES (?, ?)")
             ->execute(['T350-' . bin2hex(random_bytes(3)), $tenant === $default ? null : $tenant]);
        return (int)$conn->lastInsertId();
    };

    $save = fn(ActorContext $c, array $in) => ContractsService::saveContract($conn, $c, $in + ['contract_number' => 'T153-' . bin2hex(random_bytes(3)), 'title' => 'Contract test'])['id'];
    $owner = fn(int $id) => contractTenantOf($conn, $id);

    // ── 1. Choosing a customer ───────────────────────────────────────────────
    echo "\nChoosing a customer:\n";
    ok('a customer contract needs a company or a person',
       ($e = serviceError(fn() => $save($adminCtx, ['party_type' => 'customer']))) !== null && str_starts_with($e, 'validation'), (string)$e);
    ok('a person must be in the company chosen',
       ($e = serviceError(fn() => $save($adminCtx, ['party_type' => 'customer', 'customer_tenant_id' => $mine, 'customer_user_id' => $uTheirs]))) !== null, (string)$e);
    ok('an analyst cannot name a customer company they cannot see',
       ($e = serviceError(fn() => $save($rCtx, ['party_type' => 'customer', 'customer_tenant_id' => $theirs]))) !== null, (string)$e);
    ok('...nor a person in one',
       ($e = serviceError(fn() => $save($rCtx, ['party_type' => 'customer', 'customer_user_id' => $uTheirs]))) !== null, (string)$e);
    ok('an unknown party type is refused',
       serviceError(fn() => $save($adminCtx, ['party_type' => 'vendor', 'customer_tenant_id' => $mine])) !== null);

    // ── 2. The owning company ────────────────────────────────────────────────
    echo "\nThe company a contract belongs to (3.5.0):\n";
    $cTheirs    = $save($adminCtx, ['tenant_id' => $theirs, 'party_type' => 'customer', 'customer_tenant_id' => $theirs, 'customer_user_id' => $uTheirs, 'contract_end' => gmdate('Y-m-d', strtotime('+10 days'))]);
    $cMine      = $save($rCtx,     ['party_type' => 'customer', 'customer_tenant_id' => $mine, 'customer_user_id' => $uMine]);
    $cDefOwned  = $save($adminCtx, ['tenant_id' => $default, 'party_type' => 'customer', 'customer_tenant_id' => $mine]);   // ours, WITH their company
    $cSupplier  = $save($adminCtx, ['party_type' => 'supplier']);                       // names no company: Default
    $cSupMine   = $save($rCtx,     ['tenant_id' => $mine, 'party_type' => 'supplier']);
    ok('POSITIVE CONTROL: the restricted analyst can add contracts in their own company', $cMine > 0 && $cSupMine > 0);
    ok('a contract naming no company, by someone who cannot reach Default, lands in their own', $owner($cMine) === $mine, (string)$owner($cMine));
    ok('one naming no company, by someone who can, lands in Default', $owner($cSupplier) === $default, (string)$owner($cSupplier));
    ok('Default is stored as NULL, like every scoped table',
       $conn->query("SELECT tenant_id FROM contracts WHERE id = $cSupplier")->fetchColumn() === null);
    ok('the restricted analyst cannot put one in a company they cannot reach',
       str_starts_with((string)serviceError(fn() => $save($rCtx, ['tenant_id' => $theirs])), 'forbidden'));
    ok('an unknown company is refused', ($e = serviceError(fn() => $save($adminCtx, ['tenant_id' => 999999]))) !== null && str_starts_with($e, 'validation'), (string)$e);

    $row = $conn->query("SELECT party_type, supplier_id, customer_tenant_id, customer_user_id FROM contracts WHERE id = $cMine")->fetch(PDO::FETCH_ASSOC);
    ok('a customer contract is stored with no supplier',
       $row['party_type'] === 'customer' && $row['supplier_id'] === null && (int)$row['customer_tenant_id'] === $mine && (int)$row['customer_user_id'] === $uMine, json_encode($row));
    ContractsService::saveContract($conn, $adminCtx, ['id' => $cTheirs, 'contract_number' => 'T153-keep', 'title' => 'Renamed only']);
    $row = $conn->query("SELECT party_type, customer_tenant_id FROM contracts WHERE id = $cTheirs")->fetch(PDO::FETCH_ASSOC);
    ok('an edit that does not mention the customer keeps it', $row['party_type'] === 'customer' && (int)$row['customer_tenant_id'] === $theirs, json_encode($row));
    ok('...nor the company it belongs to', $owner($cTheirs) === $theirs);

    echo "\nContract numbers are unique per company:\n";
    $num = 'T350-DUP-' . bin2hex(random_bytes(2));
    $save($adminCtx, ['tenant_id' => $mine, 'contract_number' => $num]);
    ok('a second one in the same company is refused',
       str_starts_with((string)serviceError(fn() => $save($adminCtx, ['tenant_id' => $mine, 'contract_number' => $num])), 'conflict'));
    ok('POSITIVE CONTROL: the same number in another company is fine',
       serviceError(fn() => $save($adminCtx, ['tenant_id' => $theirs, 'contract_number' => $num])) === null);

    // ── 3. Who sees what ─────────────────────────────────────────────────────
    echo "\nWho sees which contract (by the company it belongs to):\n";
    $sees = fn(int $a, int $id) => contractCanView($conn, $a, $id);
    ok('admin sees another company\'s contract (POSITIVE CONTROL)', $sees($admin, $cTheirs));
    ok('restricted analyst does NOT see it', !$sees($r, $cTheirs));
    ok('restricted analyst does NOT see a Default contract WITH their company - the owner decides', !$sees($r, $cDefOwned));
    ok('restricted analyst does NOT see a Default supplier contract', !$sees($r, $cSupplier));
    ok('restricted analyst sees their company\'s customer contract (POSITIVE CONTROL)', $sees($r, $cMine));
    ok('restricted analyst sees their company\'s supplier contract (POSITIVE CONTROL)', $sees($r, $cSupMine));
    ok('the retired visibility setting, left at "all", opens nothing', !$sees($r, $cTheirs));

    [$vis, $args] = contractVisibilitySql($conn, $r, 'c');
    $ids = $conn->prepare("SELECT c.id FROM contracts c WHERE c.id IN (?, ?, ?, ?, ?)$vis");
    $ids->execute(array_merge([$cTheirs, $cMine, $cDefOwned, $cSupplier, $cSupMine], $args));
    $got = array_map('intval', $ids->fetchAll(PDO::FETCH_COLUMN)); sort($got);
    $want = [$cMine, $cSupMine]; sort($want);
    ok('the filter returns exactly their own company\'s two', $got === $want, json_encode($got));
    [$vis, $args] = contractVisibilitySqlForScope($conn, [], 'c');
    $none = $conn->prepare("SELECT COUNT(*) FROM contracts c WHERE 1=1$vis"); $none->execute($args);
    ok('a scope with no companies sees no contracts at all (fails closed)', (int)$none->fetchColumn() === 0);

    echo "\nEverywhere else a contract can be read:\n";
    ok('preview: hidden', recordPreview($conn, $r, 'contract', $cTheirs) === null);
    ok('preview: POSITIVE CONTROL', recordPreview($conn, $r, 'contract', $cMine) !== null);
    $def = documentEntityDef('contract');
    ok('documents: hidden', !($def['can'])($conn, $r, $cTheirs));
    ok('documents: POSITIVE CONTROL', ($def['can'])($conn, $r, $cMine));
    require_once __DIR__ . '/../includes/contract_report.php';
    ok('equipment report: hidden', contractReportLoad($conn, $cTheirs, $r) === null);
    ok('equipment report: POSITIVE CONTROL', contractReportLoad($conn, $cMine, $r) !== null);
    ok('update of a hidden contract is "not found"', str_starts_with((string)serviceError(fn() => ContractsService::saveContract($conn, $rCtx, ['id' => $cTheirs, 'title' => 'x'])), 'not_found'));
    ok('delete of a hidden contract is "not found"', str_starts_with((string)serviceError(fn() => ContractsService::deleteContract($conn, $rCtx, $cTheirs)), 'not_found'));
    ok('POSITIVE CONTROL: they can update their own', serviceError(fn() => ContractsService::saveContract($conn, $rCtx, ['id' => $cMine, 'title' => 'Mine renamed'])) === null);

    require_once __DIR__ . '/../api/v1/lib/response.php';
    require_once __DIR__ . '/../api/v1/resources/contracts.php';
    [$apiVis, $apiArgs] = apiContractVisibility($conn, ['company_scope' => [$mine]]);
    $s = $conn->prepare(apiContractSelect($conn) . " WHERE c.id = ?$apiVis");
    $s->execute(array_merge([$cTheirs], $apiArgs));
    ok('API: a key scoped to one company does not get the other\'s', $s->fetch() === false);
    $s = $conn->prepare(apiContractSelect($conn) . " WHERE c.id = ?$apiVis");
    $s->execute(array_merge([$cMine], $apiArgs));
    $one = $s->fetch(PDO::FETCH_ASSOC);
    $ser = $one ? apiSerializeContract($one) : null;
    ok('API: POSITIVE CONTROL, with its company and its customer',
       $ser && $ser['company']['id'] === $mine && $ser['party_type'] === 'customer'
       && $ser['customer']['company']['id'] === $mine && $ser['customer']['person']['id'] === $uMine, json_encode([$ser['company'] ?? null, $ser['customer'] ?? null]));
    $s = $conn->prepare(apiContractSelect($conn) . " WHERE c.id = ?");
    $s->execute([$cSupplier]);
    $ser = apiSerializeContract($s->fetch(PDO::FETCH_ASSOC));
    ok('API: a Default contract says Default, never null', ($ser['company']['id'] ?? null) === $default, json_encode($ser['company']));

    require_once __DIR__ . '/../includes/watchtower_queries.php';
    $wtAdmin = getWatchtowerData($conn, $admin)['contracts']['expiring_30d'] ?? null;
    $wtR     = getWatchtowerData($conn, $r)['contracts']['expiring_30d'] ?? null;
    ok('Watchtower: the hidden contract ending in 10 days is not counted for them', $wtAdmin !== null && $wtR !== null && $wtR < $wtAdmin, "admin {$wtAdmin}, analyst {$wtR}");

    // ── 4. Links stay inside one company ─────────────────────────────────────
    echo "\nWhat a contract links to stays in its company:\n";
    require_once __DIR__ . '/../includes/contract_assets.php';
    $aMine   = $mkAsset($mine);
    $aTheirs = $mkAsset($theirs);
    $linkErr = function (int $who, int $contract, int $asset): ?string {
        global $conn;
        try { contractAssetLink($conn, $who, $contract, $asset, null); return null; } catch (RuntimeException $e) { return $e->getMessage(); }
    };
    ok('an asset cannot be linked to a contract its analyst cannot see', $linkErr($r, $cTheirs, $aMine) !== null);
    ok('even an all-company admin cannot put another company\'s asset on a contract',
       ($m = $linkErr($admin, $cMine, $aTheirs)) !== null && stripos($m, 'different company') !== false, (string)$m);
    ok('POSITIVE CONTROL: an asset of the contract\'s own company links', $linkErr($r, $cMine, $aMine) === null);
    $cands = array_column(contractAssetSearch($conn, $admin, $cMine, 'T350-'), 'asset_id');
    ok('the asset picker offers only the contract\'s company\'s assets', in_array($aMine, $cands, true) === false && !in_array($aTheirs, $cands, true), json_encode($cands));
    $aMine2 = $mkAsset($mine);
    $cands = array_column(contractAssetSearch($conn, $admin, $cMine, 'T350-'), 'asset_id');
    ok('POSITIVE CONTROL: ...and does offer an unlinked one of its own', in_array($aMine2, $cands, true) && !in_array($aTheirs, $cands, true), json_encode($cands));

    ok('moving a contract away from its linked assets is refused',
       ($e = serviceError(fn() => ContractsService::saveContract($conn, $adminCtx, ['id' => $cMine, 'tenant_id' => $theirs]))) !== null && str_starts_with($e, 'conflict'), (string)$e);
    ok('POSITIVE CONTROL: a contract with nothing linked moves',
       serviceError(fn() => ContractsService::saveContract($conn, $adminCtx, ['id' => $cSupMine, 'tenant_id' => $theirs])) === null && $owner($cSupMine) === $theirs);
    ok('...and its former company\'s analyst no longer sees it', !$sees($r, $cSupMine));

    // ── 5. A domain's customer person, and its contract ─────────────────────
    echo "\nA domain's customer person and contract:\n";
    ok('a person in the domain\'s company is allowed', domainCustomerPersonOk($conn, $uMine, $mine));
    ok('a person in another company is not', !domainCustomerPersonOk($conn, $uTheirs, $mine));
    ok('a no-company person fits a Default-company domain', domainCustomerPersonOk($conn, $uNone, null) && domainCustomerPersonOk($conn, $uNone, $default));
    ok('...and no other', !domainCustomerPersonOk($conn, $uNone, $mine));
    $found = array_column(domainCustomerSearch($conn, $mine, 'Test '), 'id');
    ok('the picker only offers people in the domain\'s company', in_array($uMine, $found, true) && !in_array($uTheirs, $found, true) && !in_array($uNone, $found, true), json_encode($found));

    $dom = DomainsService::createDomain($conn, $adminCtx, ['domain_name' => 't153-' . bin2hex(random_bytes(3)) . '.invalid'], $mine);
    ok('a domain cannot be given someone from another company',
       ($e = serviceError(fn() => DomainsService::updateDomain($conn, $adminCtx, $dom, ['customer_user_id' => $uTheirs]))) !== null && str_starts_with($e, 'validation'), (string)$e);
    ok('POSITIVE CONTROL: someone from its own company is saved',
       serviceError(fn() => DomainsService::updateDomain($conn, $adminCtx, $dom, ['customer_user_id' => $uMine])) === null
       && (int)$conn->query("SELECT customer_user_id FROM domains WHERE id = $dom")->fetchColumn() === $uMine);
    ok('a domain cannot be linked to a contract its editor cannot see',
       serviceError(fn() => DomainsService::updateDomain($conn, $rCtx, $dom, ['contract_id' => $cTheirs])) !== null);
    ok('...nor, even by an admin, to another company\'s contract',
       ($e = serviceError(fn() => DomainsService::updateDomain($conn, $adminCtx, $dom, ['contract_id' => $cTheirs]))) !== null && stripos($e, 'different company') !== false, (string)$e);
    ok('POSITIVE CONTROL: it can be linked to its own company\'s',
       serviceError(fn() => DomainsService::updateDomain($conn, $rCtx, $dom, ['contract_id' => $cMine])) === null);
} catch (Throwable $e) {
    ok('the run completed', false, get_class($e) . ': ' . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine());
} finally {
    if ($conn->inTransaction()) $conn->rollBack();
}

$left = (int)$conn->query("SELECT COUNT(*) FROM users WHERE email LIKE '%.153test@example.invalid'")->fetchColumn()
      + (int)$conn->query("SELECT COUNT(*) FROM assets WHERE hostname LIKE 'T350-%'")->fetchColumn()
      + (int)$conn->query("SELECT COUNT(*) FROM contracts WHERE contract_number LIKE 'T153-%' OR contract_number LIKE 'T350-%'")->fetchColumn()
      + (int)$conn->query("SELECT COUNT(*) FROM system_settings WHERE setting_key = 'contracts_customer_visibility' AND setting_value = 'all'")->fetchColumn();
ok('nothing survived the run (rolled back)', $left === 0, "{$left} test rows left");

echo "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
