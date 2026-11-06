<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
/** Run prepare on a disposable OLD fork schema, db_verify_cli --apply, then finish. */
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../includes/functions.php';
require_once __DIR__.'/../includes/fork_upgrade.php';
$db=connectToDatabase();
if (!getenv('FREEITSM_TEST_DATABASE') || getenv('FREEITSM_TEST_DATABASE')!==$db->query('SELECT DATABASE()')->fetchColumn()) {
    fwrite(STDERR,"Set FREEITSM_TEST_DATABASE to an isolated test database.\n"); exit(2);
}
function check($ok,string $message):void { if(!$ok)throw new RuntimeException($message); echo "PASS $message\n"; }
if (($argv[1]??'')==='prepare') {
    check(!forkUpgradeDone($db,'4.10-prepare'),'old schema has no upgrade marker');
    foreach (['all'=>null,'restricted'=>0,'empty'=>0,'group'=>0] as $name=>$flag) {
        $db->prepare('INSERT INTO analysts(username,password_hash,full_name,email,can_access_all_modules) VALUES(?,?,?,?,?)')->execute(['zzupgrade-'.$name,password_hash(bin2hex(random_bytes(20)),PASSWORD_DEFAULT),$name,$name.'@example.test',$flag]);
    }
    $ids=$db->query("SELECT username,id FROM analysts WHERE username LIKE 'zzupgrade-%'")->fetchAll(PDO::FETCH_KEY_PAIR);
    $db->exec('CREATE TABLE IF NOT EXISTS ad_profiles (id INT AUTO_INCREMENT PRIMARY KEY,email VARCHAR(255),analyst_id INT NULL,user_id INT NULL)');
    $db->prepare("INSERT INTO ad_profiles(email,analyst_id) VALUES('zzupgrade-pending@example.test',?)")->execute([$ids['zzupgrade-restricted']]);
    $profile=(int)$db->lastInsertId();
    try { forkUpgrade($db); throw new LogicException('Linked legacy profile accepted'); }
    catch (RuntimeException $e) { check(str_contains($e->getMessage(),'Unmigrated'),'linked legacy AD profile blocks upgrade'); }
    $db->prepare('UPDATE ad_profiles SET analyst_id=NULL WHERE id=?')->execute([$profile]);
    $db->prepare("INSERT INTO analyst_modules(analyst_id,module_key) VALUES(?,'tickets')")->execute([$ids['zzupgrade-restricted']]);
    $db->exec("INSERT INTO access_groups(group_key,display_name) VALUES('zzupgrade','Upgrade fixture')"); $group=(int)$db->lastInsertId();
    $db->prepare("INSERT INTO access_group_modules(access_group_id,module_key) VALUES(?,'knowledge')")->execute([$group]);
    $db->prepare('INSERT INTO analyst_access_groups(analyst_id,access_group_id) VALUES(?,?)')->execute([$ids['zzupgrade-group'],$group]);
    $db->exec("INSERT INTO auth_providers(display_name,issuer_url,client_id,enabled) VALUES('zzupgrade-oidc','https://issuer.example.test','fixture',0)");
    $provider=(int)$db->lastInsertId();
    $db->prepare("INSERT INTO auth_provider_group_mappings(provider_id,external_group_value,access_group_id) VALUES(?,'upgrade-knowledge-readers',?)")->execute([$provider,$group]);
    $secret=bin2hex(random_bytes(20)); $smtpSecret=bin2hex(random_bytes(20));
    $db->prepare("INSERT INTO target_mailboxes(name,provider,provider_type,azure_tenant_id,azure_client_id,azure_client_secret,oauth_redirect_uri,imap_server,imap_host,imap_username,imap_password_encrypted,imap_folder,smtp_host,smtp_username,smtp_password_encrypted,target_mailbox,imap_port,imap_encryption,smtp_port,smtp_encryption) VALUES('zzupgrade','imap_smtp','imap_smtp','','','','',?,?,?,?,?,?,?,?,?,3143,'none',3025,'none')")
        ->execute([encryptValue('greenmail'),encryptValue('greenmail'),encryptValue('read@example.test'),encryptValue($secret),'Support',encryptValue('greenmail'),encryptValue('send@example.test'),encryptValue($smtpSecret),encryptValue('helpdesk@example.test')]);
    $before=forkUpgradeColumns($db,'target_mailboxes');
    forkUpgrade($db);
    check(true,'unlinked legacy lookup can remain archived');
    check($before===forkUpgradeColumns($db,'target_mailboxes'),'preview changes no schema');
    $db->exec("UPDATE target_mailboxes SET outbound_enabled=0 WHERE name='zzupgrade'");
    try { forkUpgrade($db,true); throw new LogicException('Unsupported mailbox accepted'); }
    catch (RuntimeException $e) { check(str_contains($e->getMessage(),'independent'),'unsupported configuration stops before migration'); }
    check(!forkUpgradeDone($db,'4.10-prepare') && $before===forkUpgradeColumns($db,'target_mailboxes'),'failed plan makes no changes');
    $db->exec("UPDATE target_mailboxes SET outbound_enabled=1 WHERE name='zzupgrade'");
    forkUpgrade($db,true);
    $m=$db->query("SELECT * FROM target_mailboxes WHERE name='zzupgrade'")->fetch(PDO::FETCH_ASSOC);
    check($m['provider']==='imap' && $m['email_folder']==='Support','legacy provider and folder converted');
    check(decryptValue($m['imap_password'])===$secret && decryptValue($m['smtp_password'])===$smtpSecret,'independent IMAP and SMTP passwords survive encrypted');
    check(str_starts_with($m['imap_password'],'ENC:') && str_starts_with($m['smtp_password'],'ENC:'),'passwords remain encrypted at rest');
    check((int)$m['imap_port']===3143 && $m['smtp_encryption']==='none','custom ports and plaintext lab settings retained');
    $users=$db->query("SELECT username,can_access_all_modules,is_admin FROM analysts WHERE username LIKE 'zzupgrade-%'")->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_ASSOC);
    check((int)$users['zzupgrade-all']['can_access_all_modules']===1 && (int)$users['zzupgrade-all']['is_admin']===1,'previous unrestricted administrator remains administrator');
    check((int)$users['zzupgrade-empty']['can_access_all_modules']===0 && (int)$users['zzupgrade-empty']['is_admin']===0,'explicit no-access account is not escalated');
    check((int)$users['zzupgrade-restricted']['is_admin']===0,'restricted account is not made administrator');
    check(!empty(forkUpgrade($db,true)['already_applied']),'prepare is idempotent');
} elseif (($argv[1]??'')==='finish') {
    forkUpgrade($db,false,true);
    forkUpgrade($db,true,true);
    $id=(int)$db->query("SELECT id FROM analysts WHERE username='zzupgrade-group'")->fetchColumn();
    check(analystCanAccessModule($db,$id,'knowledge'),'legacy access group works through upstream team permissions');
    check(!analystCanAccessModule($db,$id,'tickets'),'group does not grant unrelated modules');
    check(!analystIsAdmin($db,$id),'module-only team does not grant administrator status');
    require_once __DIR__.'/../includes/rbac.php';
    check(!analystHasCapability($db,$id,Cap::KNOWLEDGE_MANAGE),'module access does not grant Knowledge settings');
    check((int)$db->query("SELECT COUNT(*) FROM sso_group_teams m JOIN auth_providers p ON p.id=m.provider_id WHERE p.display_name='zzupgrade-oidc' AND m.group_name='upgrade-knowledge-readers'")->fetchColumn()===1,'legacy OIDC group mapping now points to the upstream team');
    $team=(int)$db->query("SELECT team_id FROM analyst_teams WHERE analyst_id=$id")->fetchColumn();
    $db->exec("DELETE FROM analyst_teams WHERE analyst_id=$id AND team_id=$team");
    // Permissions cache is per request, so inspect persisted grants directly here.
    check(!(int)$db->query("SELECT COUNT(*) FROM analyst_modules WHERE analyst_id=$id")->fetchColumn(),'group grant was not frozen as a permanent individual grant');
    check(!empty(forkUpgrade($db,true,true)['already_applied']),'finish is idempotent');
} else { throw new RuntimeException('Choose prepare or finish.'); }
