<?php
require __DIR__.'/common.php';
require_once __DIR__.'/../../includes/ad/Admin.php';
try {
    $db=connectToDatabase(); adRequireAdmin($db);
    if ($_SERVER['REQUEST_METHOD']==='GET') {
        $cfg=adConfig($db); $providers=adProviders($db);
        foreach ($providers as &$p) { $p['has_secret']=!empty($p['bind_password']); unset($p['bind_password'],$p['config_json'],$p['client_secret']); }
        unset($p);
        $profileId=(int)($_GET['profile_id'] ?? 0); $profile=null;
        if ($profileId) {
            $profile=adProfile($db,$profileId);
            $profile['identities']=adQuery($db,'SELECT provider_id,object_guid,directory_email,last_login_at FROM ad_identities WHERE profile_id=?',[$profileId])->fetchAll(PDO::FETCH_ASSOC);
            $profile['history']=adQuery($db,'SELECT event,details,created_at FROM ad_audit WHERE profile_id=? ORDER BY id DESC LIMIT 50',[$profileId])->fetchAll(PDO::FETCH_ASSOC);
            $profile['effective_modules']=adModules($db,$profile);
        }
        echo json_encode(['success'=>true,'config'=>$cfg,'providers'=>$providers,'profile'=>$profile,
            'profiles'=>$db->query('SELECT p.id,p.user_id,p.analyst_id,u.email FROM ad_profiles p JOIN users u ON u.id=p.user_id ORDER BY p.id DESC LIMIT 200')->fetchAll(PDO::FETCH_ASSOC),
            'groups'=>$db->query('SELECT id,display_name FROM access_groups WHERE is_active=1')->fetchAll(PDO::FETCH_ASSOC),
            'mappings'=>$db->query('SELECT * FROM ad_role_mappings')->fetchAll(PDO::FETCH_ASSOC)]); exit;
    }
    $in=adInput(); $action=$in['action'] ?? '';
    switch ($action) {
        case 'config': adSaveConfig($db,$in); break;
        case 'provider': adSaveProvider($db,$in); break;
        case 'link': adLinkExisting($db,$in,new AdLdapDirectory()); break;
        case 'lookup':
            adRateLimit($db,'admin-lookup',(string)$_SESSION['analyst_id'],5,60);
            $p=null; foreach(adProviders($db) as $candidate) if ((int)$candidate['id']===(int)($in['provider_id']??0) && $candidate['enabled']) $p=$candidate;
            if (!$p) throw new RuntimeException('Enabled provider required');
            $identity=(new AdLdapDirectory())->find($p,adNormalizeEmail((string)($in['email']??'')));
            adAudit($db,'directory_test',null,['provider_id'=>(int)$p['id']]);
            echo json_encode(['success'=>true,'identity'=>$identity]); exit;
        case 'refresh':
            $profile=adProfile($db,(int)($in['profile_id'] ?? 0));
            adRateLimit($db,'admin-refresh',(string)$profile['id'],1,30);
            adRefresh($db,$profile,new AdLdapDirectory()); break;
        case 'mapping':
            $pid=(int)($in['provider_id'] ?? 0); $guid=strtolower((string)($in['group_guid'] ?? '')); $gid=(int)($in['access_group_id'] ?? 0);
            if (!preg_match('/^[a-f0-9]{32}$/D',$guid) || !adQuery($db,"SELECT id FROM auth_providers WHERE id=? AND protocol='ad'",[$pid])->fetchColumn()) throw new InvalidArgumentException('AD provider and group GUID required');
            $db->beginTransaction();
            adQuery($db,'SELECT id FROM ad_config WHERE id=1 FOR UPDATE');
            if (!empty($in['remove'])) adQuery($db,'DELETE FROM ad_role_mappings WHERE provider_id=? AND group_guid=? AND access_group_id=?',[$pid,$guid,$gid]);
            else adQuery($db,'INSERT INTO ad_role_mappings (provider_id,group_guid,access_group_id,priority) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE priority=VALUES(priority)',[$pid,$guid,$gid,(int)($in['priority'] ?? 0)]);
            $db->exec('UPDATE ad_config SET revision=revision+1 WHERE id=1');
            adAudit($db,'mapping_changed',null,['provider_id'=>$pid,'group_guid'=>$guid,'access_group_id'=>$gid]);
            $db->commit(); break;
        default: throw new RuntimeException('Unknown action');
    }
    echo json_encode(['success'=>true]);
} catch (Throwable $e) {
    if (isset($db) && $db->inTransaction()) $db->rollBack();
    http_response_code(400);
    // Only validated business errors may be returned. Never expose LDAP/PDO exception details.
    echo json_encode(['success'=>false,'error'=> $e instanceof PDOException ? 'Database operation failed; check migration and identity conflicts.' : $e->getMessage()]);
}
