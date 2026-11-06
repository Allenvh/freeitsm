<?php
/**
 * Assets -> Leases (3.5.0): Put assets on a schedule. Returns how many went on and why any were refused.
 * Rules in includes/leases.php.
 */
require __DIR__ . '/_boot.php';
requireModuleAccessJson('assets');   // also inside leaseEndpoint(); here so the endpoint audit (D005) can see it

leaseEndpoint('POST', function (PDO $conn, ActorContext $ctx, array $in) {
    $ids = is_array($in['asset_ids'] ?? null) ? $in['asset_ids'] : [];
    return leaseAssetsAdd($conn, $ctx, (int)($in['schedule_id'] ?? 0), $ids);
});
