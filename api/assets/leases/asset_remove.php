<?php
/**
 * Assets -> Leases (3.5.0): Take an asset off a schedule altogether (a mistake - a return is an outcome).
 * Rules in includes/leases.php.
 */
require __DIR__ . '/_boot.php';
requireModuleAccessJson('assets');   // also inside leaseEndpoint(); here so the endpoint audit (D005) can see it

leaseEndpoint('POST', function (PDO $conn, ActorContext $ctx, array $in) {
    leaseAssetRemove($conn, $ctx, (int)($in['id'] ?? 0));
    return [];
});
