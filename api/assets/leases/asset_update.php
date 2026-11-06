<?php
/**
 * Assets -> Leases (3.5.0): Change one asset on a schedule: outcome, its own dates, notes.
 * Rules in includes/leases.php.
 */
require __DIR__ . '/_boot.php';
requireModuleAccessJson('assets');   // also inside leaseEndpoint(); here so the endpoint audit (D005) can see it

leaseEndpoint('POST', function (PDO $conn, ActorContext $ctx, array $in) {
    leaseAssetUpdate($conn, $ctx, (int)($in['id'] ?? 0), array_intersect_key($in, array_flip(['outcome_id', 'outcome_date', 'start_date', 'end_date', 'notes'])));
    return [];
});
