<?php
/**
 * Assets -> Leases (3.5.0): what the lease form offers for one company - the
 * companies this analyst can reach, suppliers, that company's active cost
 * centres, that company's contracts they may see, and the outcomes.
 *
 * GET ?company_id= (default: the active company; one they cannot reach is ignored)
 *     &keep_cost_centre= (the lease's current one, kept even if since made inactive)
 */
require __DIR__ . '/_boot.php';
requireModuleAccessJson('assets');   // also inside leaseEndpoint(); here so the endpoint audit (D005) can see it

leaseEndpoint('GET', function (PDO $conn, ActorContext $ctx, array $in) {
    $aid = $ctx->actorId;
    $tenant = (int)($in['company_id'] ?? 0);
    if ($tenant <= 0 || (isMultiTenant($conn) && !analystCanAccessTenant($conn, $aid, $tenant))) {
        $tenant = getActiveTenantId($conn, $aid);
    }

    $companies = [];
    if (isMultiTenant($conn)) {
        $mine = getAccessibleTenantIds($conn, $aid);
        foreach (getAllTenants($conn, true) as $t) {
            if (in_array($t['id'], $mine, true)) $companies[] = ['id' => $t['id'], 'name' => $t['name']];
        }
    }

    // The lessor may not be flagged as an asset supplier, so every active
    // supplier is offered - asset suppliers first.
    $suppliers = $conn->query("SELECT id, COALESCE(NULLIF(TRIM(trading_name), ''), legal_name) AS name, supplies_assets
                                 FROM suppliers WHERE is_active = 1 ORDER BY supplies_assets DESC, name")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($suppliers as &$s) { $s['id'] = (int)$s['id']; $s['supplies_assets'] = (int)$s['supplies_assets']; }
    unset($s);

    require_once __DIR__ . '/../../../includes/cost_centre_assign.php';
    $keep = (int)($in['keep_cost_centre'] ?? 0) ?: null;
    $costCentres = costCentreReady($conn, 'leases') ? costCentreOptions($conn, $tenant, $keep) : [];

    $contracts = [];
    if (moduleIsEnabled($conn, 'contracts')) {   // GH #217: none to offer while Contracts is switched off
        require_once __DIR__ . '/../../../includes/contract_party.php';
        [$vSql, $vP] = contractVisibilitySql($conn, $aid, 'c');
        [$tSql, $tP] = contractTenantReady($conn) ? tenantScopeSqlFor($conn, $tenant, 'c.tenant_id') : ['', []];
        $st = $conn->prepare("SELECT c.id, c.contract_number, c.title FROM contracts c WHERE 1=1$tSql$vSql ORDER BY c.title LIMIT 1000");
        $st->execute(array_merge($tP, $vP));
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $c) {
            $contracts[] = ['id' => (int)$c['id'], 'name' => trim(($c['contract_number'] ? $c['contract_number'] . ' - ' : '') . $c['title'])];
        }
    }

    return [
        'company_id'   => $tenant,
        'companies'    => $companies,
        'suppliers'    => $suppliers,
        'cost_centres' => $costCentres,
        'contracts'    => $contracts,
        'outcomes'     => leaseOutcomes($conn),
    ];
});
