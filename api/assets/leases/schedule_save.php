<?php
/**
 * Assets -> Leases (3.5.0): Create or update a schedule on a lease.
 * Rules in includes/leases.php.
 */
require __DIR__ . '/_boot.php';
requireModuleAccessJson('assets');   // also inside leaseEndpoint(); here so the endpoint audit (D005) can see it

leaseEndpoint('POST', function (PDO $conn, ActorContext $ctx, array $in) {
    return ['id' => leaseScheduleSave($conn, $ctx, $in)];
});
