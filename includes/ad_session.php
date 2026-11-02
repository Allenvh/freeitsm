<?php
/** Enforce directory session revocation and authorization on every configured PHP entry point. */
function adSessionDeny(string $message, int $code = 403): never {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['success'=>false,'error'=>$message]);
    exit;
}
function adEnforceSession(): void {
    if (empty($_SESSION['ad_profile_id'])) return;
    require_once __DIR__.'/ad.php';
    try {
        $db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8mb4',DB_USERNAME,DB_PASSWORD,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        $cfg = adConfig($db);
        $provider = adQuery($db,"SELECT p.id FROM auth_providers p JOIN ad_provider_config c ON c.provider_id=p.id WHERE p.id=? AND p.enabled=1 AND p.protocol='ad' AND c.authentication_enabled=1",[$_SESSION['ad_provider_id'] ?? 0])->fetchColumn();
        if (!$cfg['enabled'] || !$provider) adSessionDeny('AD session revoked; sign in again',401);
        $profile = adProfile($db,(int)$_SESSION['ad_profile_id']);
        $path = ltrim(substr($_SERVER['SCRIPT_NAME'] ?? '',strlen(BASE_URL)),'/');
        $portal = str_starts_with($path,'self-service/') || str_starts_with($path,'api/self-service/');
        $account = in_array($path,['api/ad/refresh.php','ad-account.php'],true);
        $sensitive = !$portal && !$account;
        if (adAuthorizationRefreshDue($cfg,$profile,$sensitive,time())) {
            // Outage retries are rate-limited across all concurrent sessions/requests.
            adRateLimit($db,'session-refresh',(string)$profile['id'],2,30);
            $profile = adRefresh($db,$profile,new AdLdapDirectory());
        }
        $modules = adModules($db,$profile);
        $_SESSION['allowed_modules'] = $modules;
        if ($modules === []) unset($_SESSION['analyst_id']);
        elseif ($profile['analyst_id']) {
            $analyst=adQuery($db,'SELECT * FROM analysts WHERE id=?',[$profile['analyst_id']])->fetch(PDO::FETCH_ASSOC);
            $_SESSION['analyst_id']=(int)$analyst['id'];
            $_SESSION['analyst_name']=$analyst['full_name'];
            $_SESSION['analyst_email']=$analyst['email'];
            $_SESSION['analyst_username']=$analyst['username'];
        }
        if (str_starts_with($path,'api/tickets/') && preg_match('/(?:analyst|mailbox|team|department|sla_|email_template|ticket_type|ticket_status|ticket_priority|ticket_origin|rota_|user)/',basename($path))
            && (preg_match('/^(?:save|delete|create|set|update|reset)_/',basename($path)) || str_contains(basename($path),'mailbox'))) {
            if ($modules !== null && !in_array('system',$modules,true)) adSessionDeny('Administrator access required');
        }
        if ($portal || $account || in_array($path,['logout.php','self-service/logout.php'],true)) return;
        if (!isset($_SESSION['analyst_id'])) adSessionDeny('Self-service access only');
        if (str_starts_with($path,'api/myaccount/')) return;
        if ($modules !== null && $path !== 'index.php') {
            $parts = explode('/',$path);
            $module = $parts[0] === 'api' ? ($parts[1] ?? '') : $parts[0];
            $module = ['assets'=>'assets','asset-management'=>'assets','change-management'=>'changes','morning-checks'=>'morning-checks','problem-management'=>'problems','system-wiki'=>'wiki','ad'=>'system','settings'=>'system','intune'=>'assets','rfp-builder'=>'contracts'][$module] ?? $module;
            if (!in_array($module,$modules,true)) adSessionDeny('Module access denied');
        }
    } catch (Throwable $e) {
        // An outage never becomes local mode. Only read-only portal requests remain usable.
        $path = $_SERVER['SCRIPT_NAME'] ?? '';
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && (str_contains($path,'/self-service/') || str_contains($path,'/api/self-service/'))) {
            unset($_SESSION['analyst_id']);
            $_SESSION['allowed_modules'] = [];
            return;
        }
        adSessionDeny('Authorization unavailable',503);
    }
}
adEnforceSession();
