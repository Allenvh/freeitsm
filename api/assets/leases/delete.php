<?php
/**
 * Assets -> Leases (3.5.0): Delete a lease (its schedules and asset rows go with it; the assets keep their dates).
 * Rules in includes/leases.php.
 */
require __DIR__ . '/_boot.php';
requireModuleAccessJson('assets');   // also inside leaseEndpoint(); here so the endpoint audit (D005) can see it

leaseEndpoint('POST', function (PDO $conn, ActorContext $ctx, array $in) {
    leaseDelete($conn, $ctx, (int)($in['id'] ?? 0));
    return [];
});
