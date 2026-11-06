<?php
/**
 * Assets -> Leases (3.5.0): find assets to put on a lease - the lease's own
 * company only, by hostname, asset tag, service tag, manufacturer or model.
 * Each says which lease it is already out on, if any (it cannot go on two).
 *
 * GET ?lease_id=&q=
 */
require __DIR__ . '/_boot.php';
requireModuleAccessJson('assets');   // also inside leaseEndpoint(); here so the endpoint audit (D005) can see it

leaseEndpoint('GET', function (PDO $conn, ActorContext $ctx, array $in) {
    $lease = leaseLoad($conn, $ctx->actorId, (int)($in['lease_id'] ?? 0));
    $tenant = leaseTenant($conn, $lease);
    $q = trim((string)($in['q'] ?? ''));
    if (mb_strlen($q) < 2) return ['assets' => []];

    [$tSql, $tP] = tenantScopeSqlFor($conn, $tenant, 'a.tenant_id');
    $like = '%' . $q . '%';
    $act = leaseActiveSql();
    $st = $conn->prepare(
        "SELECT a.id, a.hostname, a.asset_tag, a.service_tag, a.manufacturer, a.model, a.lease_start, a.lease_expiry,
                (SELECT l.name FROM lease_assets la JOIN lease_schedules s ON s.id = la.schedule_id JOIN leases l ON l.id = s.lease_id
                   LEFT JOIN lease_outcomes lo ON lo.id = la.outcome_id WHERE la.asset_id = a.id AND $act LIMIT 1) AS on_lease
           FROM assets a
          WHERE (a.hostname LIKE ? OR a.asset_tag LIKE ? OR a.service_tag LIKE ? OR a.manufacturer LIKE ? OR a.model LIKE ?)$tSql
       ORDER BY a.hostname LIMIT 50"
    );
    $st->execute(array_merge([$like, $like, $like, $like, $like], $tP));
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$r) $r['id'] = (int)$r['id'];
    return ['assets' => $rows];
});
