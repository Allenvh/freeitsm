<?php
require __DIR__.'/common.php';
try {
    $in=adInput();
    adRequestVerification(connectToDatabase(),(string)($in['email'] ?? ''));
} catch (Throwable $e) {
    // Same response for existing, disallowed, throttled, and undeliverable addresses.
}
echo json_encode(['success'=>true,'message'=>'If this address is eligible, a verification link has been sent. Existing accounts require administrator review.']);
