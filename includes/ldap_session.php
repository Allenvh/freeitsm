<?php
// Old custom AD sessions cannot retain their cached elevated privileges after retirement.
if (PHP_SAPI !== 'cli' && !empty($_SESSION['ad_profile_id'])) {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION = [];
    session_regenerate_id(true);
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}
// Apply the fork's module permissions to native directory sessions. This does not
// contact AD, read custom grants, or implement periodic directory authorization.
if (PHP_SAPI !== 'cli' && (!empty($_SESSION['analyst_id']) || !empty($_SESSION['ss_user_id']))) {
    require_once __DIR__ . '/functions.php';
    require_once __DIR__ . '/ldap_transition.php';
    try {
        $db=connectToDatabase();
        $path=ltrim(substr($_SERVER['SCRIPT_NAME']??'',strlen(rtrim(BASE_URL,'/'))),'/');
        $recovery=in_array($path,['login.php','logout.php','analyst_logout.php','self-service/login.php','self-service/logout.php','ad-login.php','ad-permissions.php'],true);
        $portal=str_starts_with($path,'self-service/')||str_starts_with($path,'api/self-service/');
        $kind=($portal&&!empty($_SESSION['ss_user_id']))||empty($_SESSION['analyst_id'])?'users':'analysts';
        $id=(int)($kind==='analysts'?$_SESSION['analyst_id']:$_SESSION['ss_user_id']);
        $st=$db->prepare("SELECT a.*,p.protocol AS sign_in_protocol,p.enabled AS provider_enabled FROM $kind a LEFT JOIN auth_providers p ON p.id=a.auth_provider_id WHERE a.id=?");$st->execute([$id]);$account=$st->fetch(PDO::FETCH_ASSOC);
        if (!$account && !$recovery) { http_response_code(403); exit('Account no longer available. Sign in again.'); }
        $expected=(int)($_SESSION[$kind==='analysts'?'ldap_analyst_provider_id':'ldap_user_provider_id']??0);
        if ($expected && !$recovery && (($account['sign_in_protocol']??'')!=='ldap'||(int)$account['auth_provider_id']!==$expected)) { http_response_code(403); exit('Sign-in method changed. Sign out and authenticate again.'); }
        $pending=legacyAdMigrationPending($db,$kind==='analysts'?'analyst_id':'user_id',$id);
        $directory=($account['sign_in_protocol']??'')==='ldap';
        $unavailable = $directory && (!(int)$account['provider_enabled'] || ($kind === 'analysts' && !(int)$account['is_active']));
        if (!$recovery && ($pending || $unavailable)) {
            http_response_code(403);exit('Directory account unavailable. Sign out and contact your administrator.');
        }
        if($directory&&$kind==='analysts'&&!$recovery){
            $allowed=getAnalystAllowedModules($db,$id);$_SESSION['allowed_modules']=$allowed;
            if($allowed!==null){
                $module=explode('/',preg_replace('~^api/~','',$path))[0];
                $module=['asset-management'=>'assets','change-management'=>'changes','problem-management'=>'problems','system-wiki'=>'wiki'][$module]??$module;
                $admin=preg_match('~^(api/)?(system|settings)/|/settings/|(?:save|delete|reset|create)_(?:analyst|user|mailbox)|(?:save|test|verify|generate)_[^/]*(?:settings|provider|config|smtp|imap)~',$path);
                $own=str_starts_with($path,'api/myaccount/')||str_starts_with($path,'self-service/')||str_starts_with($path,'api/self-service/');
                if(($admin&&!in_array('system',$allowed,true))||(!$own&&$module!=='index.php'&&!in_array($module,$allowed,true))){http_response_code(403);exit('This module is not permitted.');}
            }
        }
    }catch(Throwable$e){http_response_code(503);exit('Account validation unavailable. Please retry.');}
}
