<?php
/* 🔴 NEVER OVER THE WEB - see tests/README.md. This one writes to the real
   tables: throwaway domains named zzcc-*.example and one inactive cost centre
   coded ZZCC, all removed in the cleanup at the bottom, including on failure. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Domains: the cost centre is a real one from System -> Cost centres (GH #188).
 *
 *   - choosing one links it and drops any old free text;
 *   - text sent the old way (REST API, CSV import, bulk edit) is a CODE in the
 *     domain's company - linked if it exists, refused if not, '' clears;
 *   - choosing "none" keeps old text rather than silently wiping it;
 *   - an inactive cost centre cannot be chosen, but one already held is kept;
 *   - the delete-guard counts domains (and leases, which it used to miss).
 *
 * Needs domains.cost_centre_id (Database Verification); without it, it checks
 * that the old free text still saves exactly as before, then stops.
 *
 * Run:  php tests/domain-cost-centre.php
 */

chdir(__DIR__ . '/..');
require_once 'config.php';
require_once 'includes/functions.php';
require_once 'includes/services/domains.php';
require_once 'includes/domains/read.php';
require_once 'includes/cost_centre_assign.php';

$pass = 0; $fail = 0;
function is_(string $what, $got, $want): void {
    global $pass, $fail;
    if ($got === $want) { $pass++; printf("  PASS  %-62s\n", $what); }
    else { $fail++; printf("  FAIL  %-62s got %s, wanted %s\n", $what, json_encode($got), json_encode($want)); }
}
/** The domain's stored cost centre: [id, text]. */
function cc(PDO $conn, int $id): array {
    $cols = costCentreReady($conn, 'domains') ? 'cost_centre_id, cost_centre' : 'NULL, cost_centre';
    $r = $conn->query("SELECT $cols FROM domains WHERE id = $id")->fetch(PDO::FETCH_NUM);
    return [$r[0] === null ? null : (int)$r[0], $r[1]];
}

$conn = connectToDatabase();
$admin = (int)$conn->query("SELECT id FROM analysts WHERE is_admin = 1 AND is_active = 1 ORDER BY id LIMIT 1")->fetchColumn();
$_SESSION = ['analyst_id' => $admin, 'analyst_name' => 'ZZCC test', 'is_admin' => 1];
$ctx = ActorContext::fromSession($conn);
$ready = costCentreReady($conn, 'domains');

echo "\nDomains: cost centre (#188)\n" . str_repeat('=', 78) . "\n";
echo "  domains.cost_centre_id " . ($ready ? "exists" : "NOT YET - run Database Verification for the full test") . "\n\n";

$domainIds = []; $inactiveId = null;
try {
    $def = getDefaultTenantId($conn);
    $active = $conn->prepare("SELECT id, code FROM cost_centres WHERE tenant_id = ? AND is_active = 1 ORDER BY id LIMIT 2");
    $active->execute([$def]);
    $two = $active->fetchAll(PDO::FETCH_ASSOC);
    if (count($two) < 2) throw new RuntimeException('Needs two active cost centres in the Default company.');
    [$cc1, $cc2] = [(int)$two[0]['id'], (int)$two[1]['id']];

    if (!$ready) {
        $id = DomainsService::createDomain($conn, $ctx, ['domain_name' => 'zzcc-old.example', 'cost_centre' => 'Typed text'], null);
        $domainIds[] = $id;
        is_('before Verification, the text saves as before', cc($conn, $id), [null, 'Typed text']);
        DomainsService::updateDomain($conn, $ctx, $id, ['cost_centre' => 'Other text']);
        is_('and updates as before', cc($conn, $id), [null, 'Other text']);
    } else {
        $id = DomainsService::createDomain($conn, $ctx, ['domain_name' => 'zzcc-one.example', 'cost_centre_id' => $cc1], null);
        $domainIds[] = $id;
        is_('create with a cost centre', cc($conn, $id), [$cc1, null]);

        DomainsService::updateDomain($conn, $ctx, $id, ['cost_centre' => $two[1]['code']]);
        is_('text the old way is a code - linked', cc($conn, $id), [$cc2, null]);

        $refused = false;
        try { DomainsService::updateDomain($conn, $ctx, $id, ['cost_centre' => 'NO-SUCH-CODE']); } catch (ServiceError $e) { $refused = true; }
        is_('an unknown code is refused', $refused, true);
        is_('and nothing changed', cc($conn, $id), [$cc2, null]);

        DomainsService::updateDomain($conn, $ctx, $id, ['cost_centre' => '']);
        is_('an empty code clears it', cc($conn, $id), [null, null]);

        // Old text from before #188: choosing "none" keeps it; choosing one replaces it.
        $conn->exec("UPDATE domains SET cost_centre = 'Legacy text', cost_centre_id = NULL WHERE id = $id");
        DomainsService::updateDomain($conn, $ctx, $id, ['cost_centre_id' => '', 'notes' => 'zz']);
        is_('saving with "none" keeps the old text', cc($conn, $id), [null, 'Legacy text']);
        DomainsService::updateDomain($conn, $ctx, $id, ['cost_centre_id' => $cc1]);
        is_('choosing one replaces the old text', cc($conn, $id), [$cc1, null]);

        // Inactive: cannot be chosen, but is kept if already held.
        $conn->prepare("INSERT INTO cost_centres (tenant_id, code, name, is_active) VALUES (?, 'ZZCC', 'ZZCC test', 0)")->execute([$def]);
        $inactiveId = (int)$conn->lastInsertId();
        $refused = false;
        try { DomainsService::updateDomain($conn, $ctx, $id, ['cost_centre_id' => $inactiveId]); } catch (ServiceError $e) { $refused = true; }
        is_('an inactive cost centre cannot be chosen', $refused, true);
        $conn->exec("UPDATE domains SET cost_centre_id = $inactiveId WHERE id = $id");
        DomainsService::updateDomain($conn, $ctx, $id, ['cost_centre_id' => $inactiveId, 'notes' => 'still']);
        is_('one already held may be saved unchanged', cc($conn, $id), [$inactiveId, null]);

        $usage = costCentreUsage($conn, $inactiveId);
        is_('the delete-guard counts the domain', $usage['domain(s)'] ?? 0, 1);

        // What the page and the API read.
        $d = domainDetail($conn, $id, $admin);
        is_('the page gets the label', $d['cost_centre_label'], 'ZZCC ZZCC test');
        is_('and offers the inactive one it already holds', in_array($inactiveId, array_column($d['cost_centre_options'], 'id'), true), true);
    }
} catch (Throwable $e) {
    $fail++;
    echo "  FAIL  unexpected: " . $e->getMessage() . "\n";
} finally {
    foreach ($domainIds as $id) { try { DomainsService::deleteDomain($conn, $ctx, $id); } catch (Throwable $e) { echo "  cleanup: " . $e->getMessage() . "\n"; } }
    if ($inactiveId) $conn->exec("DELETE FROM cost_centres WHERE id = $inactiveId");
    $left = (int)$conn->query("SELECT (SELECT COUNT(*) FROM domains WHERE domain_name LIKE 'zzcc-%') + (SELECT COUNT(*) FROM cost_centres WHERE code = 'ZZCC')")->fetchColumn();
    is_('cleanup: nothing named zzcc / ZZCC is left', $left, 0);
}

echo "\n" . str_repeat('-', 78) . "\n  $pass passed, $fail failed\n\n";
exit($fail ? 1 : 0);
