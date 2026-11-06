<?php
/**
 * Assets -> Leases (3.5.0): One lease with its schedules and the assets on them.
 * Rules in includes/leases.php.
 */
require __DIR__ . '/_boot.php';
requireModuleAccessJson('assets');   // also inside leaseEndpoint(); here so the endpoint audit (D005) can see it

leaseEndpoint('GET', function (PDO $conn, ActorContext $ctx, array $in) {
    return ['lease' => leaseGet($conn, $ctx->actorId, (int)($in['id'] ?? 0))];
});
