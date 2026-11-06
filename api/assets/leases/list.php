<?php
/**
 * Assets -> Leases (3.5.0): Every lease in the active company (or all of them), with dates and counts.
 * Rules in includes/leases.php.
 */
require __DIR__ . '/_boot.php';
requireModuleAccessJson('assets');   // also inside leaseEndpoint(); here so the endpoint audit (D005) can see it

leaseEndpoint('GET', function (PDO $conn, ActorContext $ctx, array $in) {
    return ['leases' => leaseList($conn, $ctx->actorId, trim((string)($in['search'] ?? '')))];
});
