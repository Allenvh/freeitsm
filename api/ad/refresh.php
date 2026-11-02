<?php
require __DIR__.'/common.php';
try {
    adInput();
    $db=connectToDatabase();
    if (empty($_SESSION['ad_profile_id'])) throw new RuntimeException('AD sign-in required');
    $p=adProfile($db,(int)$_SESSION['ad_profile_id']);
    if (!adConfig($db)['authority_provider_id']) {
        echo json_encode(['success'=>true,'source'=>'FreeITSM','message'=>'Permissions are managed in FreeITSM.']); exit;
    }
    adRateLimit($db,'manual-refresh',(string)$p['id'],1,30);
    $p=adRefresh($db,$p,new AdLdapDirectory());
    $provider=(int)$_SESSION['ad_provider_id'];
    adSetSession($db,$p,$provider);
    echo json_encode(['success'=>true,'source'=>'Active Directory','updated_at'=>$p['authorization_checked_at'],'modules'=>$_SESSION['allowed_modules'] ?? [],'csrf'=>adCsrf()]);
} catch (Throwable $e) { http_response_code(503); echo json_encode(['success'=>false,'error'=>'Permissions could not be refreshed. Contact an administrator.']); }
