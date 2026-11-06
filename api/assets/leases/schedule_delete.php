<?php
/**
 * Assets -> Leases (3.5.0): Delete a schedule (not the last one).
 * Rules in includes/leases.php.
 */
require __DIR__ . '/_boot.php';
requireModuleAccessJson('assets');   // also inside leaseEndpoint(); here so the endpoint audit (D005) can see it

leaseEndpoint('POST', function (PDO $conn, ActorContext $ctx, array $in) {
    leaseScheduleDelete($conn, $ctx, (int)($in['id'] ?? 0));
    return [];
});
