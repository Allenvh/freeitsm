<?php
require __DIR__.'/common.php';
try {
    $in = adInput();
     $db=connectToDatabase();
    echo json_encode(adLogin($db,(string)($in['email'] ?? ''),(string)($in['password'] ?? ''),!empty($in['provider_id']) ? (int)$in['provider_id'] : null));
} catch (Throwable $e) {
    if (isset($db)) { try { adAudit($db,'login_rejected'); } catch (Throwable $ignored) {} }
    http_response_code(401);
    // Do not expose account existence, provider details, bind errors, or secrets.
    echo json_encode(['success'=>false,'error'=>'Sign-in failed or authorization unavailable. Verify your email or contact an administrator.']);
}
