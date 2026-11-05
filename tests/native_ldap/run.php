<?php
require_once __DIR__.'/../../includes/legacy_ad_migration.php';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../../includes/native_ldap_provider.php';
$db=new PDO('mysql:host='.(getenv('LDAP_TEST_DB_HOST')?:'db').';charset=utf8mb4',getenv('LDAP_TEST_DB_USER')?:'root',getenv('LDAP_TEST_DB_PASSWORD') ?: throw new RuntimeException('Set LDAP_TEST_DB_PASSWORD for the disposable test database.'),[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$db->exec('DROP DATABASE IF EXISTS freeitsm_native_ldap_test');$db->exec('CREATE DATABASE freeitsm_native_ldap_test');$db->exec('USE freeitsm_native_ldap_test');
$db->exec(file_get_contents(__DIR__.'/../../database/freeitsm.sql'));
// Simulate the pre-LDAP schema, then exercise the real upgrade twice.
$oldCols=$db->query('SHOW COLUMNS FROM auth_providers')->fetchAll(PDO::FETCH_COLUMN);
foreach($oldCols as$col)if(str_starts_with($col,'ldap_')||$col==='auto_create_analysts')$db->exec("ALTER TABLE auth_providers DROP COLUMN `$col`");
$db->exec('ALTER TABLE analysts DROP COLUMN can_access_all_modules');
$db->exec('ALTER TABLE users DROP INDEX uq_users_username, DROP COLUMN username, DROP COLUMN tenant_id, MODIFY email VARCHAR(255) NOT NULL');
ensureNativeLdapSchema($db);ensureNativeLdapSchema($db);
function check(bool$v,string$m="assertion"):void{if(!$v)throw new RuntimeException($m);}
function query(string$sql,array$args=[]):PDOStatement{global$db;$s=$db->prepare($sql);$s->execute($args);return$s;}
$tests=0;function test(string$name,callable$f):void{global$tests;$f();$tests++;echo"PASS $name\n";}
test('fresh schema and repeated upgrade preserve local emergency login',function(){check(password_verify('freeitsm',query("SELECT password_hash FROM analysts WHERE username='admin'")->fetchColumn()),'Local password changed');});
test('upstream password and NUL guards reject before LDAP connection',function(){foreach(['',"\0",'abc'."\0".'x']as$p)check(ldapAuthenticate([], 'alice',$p)['reason']==='credentials','Unsafe bind allowed');});
test('upstream group gates distinguish analyst, portal and denied access',function(){check(ldapAccessRole(['ldap_analyst_group'=>'IT'],['groups'=>[['dn'=>'CN=IT,DC=example,DC=test','cn'=>'IT']]])==='analyst','Analyst gate');check(ldapAccessRole(['ldap_user_group'=>'Portal'],['groups'=>[['dn'=>'CN=Portal,DC=example,DC=test','cn'=>'Portal']]])==='user','Portal gate');check(ldapAccessRole(['ldap_analyst_group'=>'IT'],['groups'=>[]])===null,'Empty groups granted');});
$secret=bin2hex(random_bytes(24));
$provider=['display_name'=>'Native test','ldap_host'=>'dc.example.test','ldap_port'=>636,'ldap_encryption'=>'ldaps','ldap_base_dn'=>'DC=example,DC=test','ldap_bind_dn'=>'svc@example.test','ldap_bind_password'=>$secret,'auto_create_users'=>1,'auto_create_analysts'=>1,'default_modules'=>'tickets,knowledge','enabled'=>1];
$pid=saveNativeLdapProvider($db,$provider);$p=ldapGetProvider($db,$pid);
test('native configuration encrypts secrets and blank edit reuses them',function()use($db,$provider,$pid,$secret){$edit=$provider;$edit['id']=$pid;$edit['ldap_bind_password']='';saveNativeLdapProvider($db,$edit);$stored=query('SELECT ldap_bind_password FROM auth_providers WHERE id=?',[$pid])->fetchColumn();check(str_starts_with($stored,'ENC:')&&$stored!==$secret,'Plaintext secret');check(ldapGetProvider($db,$pid)['ldap_bind_password']===$secret,'Secret lost');});
$user=['guid'=>str_repeat('a',32),'email'=>'alice@example.test','username'=>'alice','name'=>'Alice','groups'=>[]];$analystId=0;$userId=0;
test('upstream JIT analyst and explicit module restrictions',function()use($db,$p,$user,&$analystId){$r=ldapResolveAnalyst($db,$p,$user);check($r['ok'],'JIT analyst failed');$analystId=$r['analyst_id'];$mods=getAnalystAllowedModules($db,$analystId);sort($mods);check($mods===['knowledge','tickets'],'Restricted analyst gained unrelated modules');});
test('upstream portal identity creation and repeat sign-in reuse internal IDs',function()use($db,$p,$user,&$userId,$analystId){$r=ldapResolveUser($db,$p,$user);check($r['ok'],'Portal provisioning failed');$userId=$r['user_id'];check(ldapResolveUser($db,$p,$user)['user_id']===$userId,'Portal duplicated');check(ldapResolveAnalyst($db,$p,$user)['analyst_id']===$analystId,'Analyst duplicated');});
test('upstream immutable link rejects a different person using the same email',function()use($db,$p,$user){$other=$user;$other['guid']=str_repeat('b',32);check(!ldapResolveAnalyst($db,$p,$other)['ok'],'Analyst email takeover');check(!ldapResolveUser($db,$p,$other)['ok'],'Portal email takeover');});
test('empty explicit modules never grant unrestricted access',function()use($db,$analystId){query('DELETE FROM analyst_modules WHERE analyst_id=?',[$analystId]);check(getAnalystAllowedModules($db,$analystId)===[],'Empty explicit grants expanded');});
// Build a legacy installation fixture using the historical, retained migration.
$db->exec(file_get_contents(__DIR__.'/../../database/migrations/20261103_active_directory.sql'));
$c=['primary'=>'dc.legacy.test','secondary'=>'dc2.legacy.test','domain_dns'=>'legacy.test','domain_suffix'=>'legacy.test','port'=>636,'transport'=>'ldaps','bind_identity'=>'svc@legacy.test','user_base'=>'','base_dn'=>'DC=legacy,DC=test','group_base'=>'','username_attribute'=>'sAMAccountName','email_attribute'=>'mail','display_attribute'=>'displayName','nested_groups'=>true,'ca_file'=>'/run/legacy-ca.pem'];
query('INSERT INTO ad_providers(id,name,config_json,password_encrypted)VALUES(1,?,?,?)',['Legacy directory',json_encode($c),encryptValue($secret)]);
query("INSERT INTO users(email,display_name)VALUES('legacy@example.test','Legacy employee')");$legacyUser=(int)$db->lastInsertId();
query("INSERT INTO analysts(username,password_hash,full_name,email,is_active)VALUES('legacy-analyst',?,'Legacy employee','legacy@example.test',1)",[password_hash(bin2hex(random_bytes(32)),PASSWORD_DEFAULT)]);$legacyAnalyst=(int)$db->lastInsertId();
query("INSERT INTO ad_profiles(id,email,verified_at,user_id,analyst_id,last_provider_id,authorization_status,authorization_json)VALUES(1,'legacy@example.test',UTC_TIMESTAMP(),?,?,1,'current',?)",[$legacyUser,$legacyAnalyst,json_encode(['modules'=>['tickets']])]);
query("INSERT INTO ad_identities(provider_id,object_guid,profile_id,directory_email)VALUES(1,?,1,'legacy@example.test')",[str_repeat('c',32)]);
query("INSERT INTO tickets(ticket_number,subject,user_id,assigned_analyst_id)VALUES('LEGACY-1','Retained history',?,?)",[$legacyUser,$legacyAnalyst]);
test('unmigrated legacy identities fail closed',function()use($db,$p){check(legacyAdMigrationPending($db,'email','legacy@example.test'),'Missing quarantine');$u=['guid'=>str_repeat('d',32),'email'=>'legacy@example.test','username'=>'legacy','name'=>'Legacy'];check(!ldapResolveUser($db,$p,$u)['ok'],'Unmigrated portal account claimed');});
test('dry run makes no identity/provider changes',function()use($db){$before=query('SELECT COUNT(*) FROM auth_providers')->fetchColumn();$r=migrateLegacyAd($db);check($r['profiles']===1,'Dry run plan');check(query('SELECT COUNT(*) FROM auth_providers')->fetchColumn()===$before,'Dry run modified providers');});
test('disabled legacy profiles stop migration without partial provider or identity writes',function()use($db){$count=query('SELECT COUNT(*) FROM auth_providers')->fetchColumn();query('UPDATE ad_profiles SET disabled=1 WHERE id=1');$failed=false;try{migrateLegacyAd($db,true);}catch(RuntimeException$e){$failed=true;}check($failed,'Disabled profile migrated');check(query('SELECT COUNT(*) FROM auth_providers')->fetchColumn()===$count,'Partial provider remained');check(query('SELECT COUNT(*) FROM legacy_ad_profile_migrations')->fetchColumn()==0,'Partial identity migrated');query('UPDATE ad_profiles SET disabled=0 WHERE id=1');});
test('conflicting provider assignments roll back migration instead of merging accounts',function()use($db,$pid,$legacyUser){query('UPDATE users SET auth_provider_id=? WHERE id=?',[$pid,$legacyUser]);$count=query('SELECT COUNT(*) FROM auth_providers')->fetchColumn();$failed=false;try{migrateLegacyAd($db,true);}catch(RuntimeException$e){$failed=true;}check($failed,'Conflicting assignment migrated');check(query('SELECT COUNT(*) FROM auth_providers')->fetchColumn()===$count,'Partial directory import');query('UPDATE users SET auth_provider_id=NULL WHERE id=?',[$legacyUser]);});
test('migration preserves identity IDs, tickets, encrypted credentials and restricted modules',function()use($db,$legacyUser,$legacyAnalyst,$secret){$r=migrateLegacyAd($db,true);check($r['profiles']===1,'Profile not migrated');$id=(int)query('SELECT provider_id FROM legacy_ad_provider_migrations WHERE legacy_provider_id=1')->fetchColumn();$provider=ldapGetProvider($db,$id);check(!$provider['enabled'],'Imported provider enabled automatically');check($provider['ldap_bind_password']===$secret,'Credentials lost');$ticket=query("SELECT user_id,assigned_analyst_id FROM tickets WHERE ticket_number='LEGACY-1'")->fetch(PDO::FETCH_ASSOC);check((int)$ticket['user_id']===$legacyUser&&(int)$ticket['assigned_analyst_id']===$legacyAnalyst,'Ticket history reassigned');check(getAnalystAllowedModules($db,$legacyAnalyst)===['tickets'],'Permission snapshot changed');check(!legacyAdMigrationPending($db,'user_id',$legacyUser),'Migration quarantine not cleared');});
test('migration is repeatable and retains legacy tables for recovery',function()use($db){$r=migrateLegacyAd($db,true);check($r['profiles']===0,'Repeated migration changed accounts');check(query('SELECT COUNT(*) FROM ad_profiles')->fetchColumn()==1,'Legacy history removed');});
test('OIDC and LDAP providers cannot be confused',function()use($db,$pid){require_once __DIR__.'/../../includes/oidc.php';check(oidcGetProvider($db,$pid)===null,'LDAP accepted by OIDC');query("INSERT INTO auth_providers(display_name,issuer_url,client_id,protocol)VALUES('Existing OIDC','https://id.example.test','freeitsm','oidc')");$id=(int)$db->lastInsertId();check(oidcGetProvider($db,$id)!==null,'Existing OIDC unavailable');check(ldapGetProvider($db,$id)===null,'OIDC accepted by LDAP');});
test('existing OIDC signature, issuer, audience and nonce validation remains functional',function(){
    require_once __DIR__.'/../../includes/oidc.php';
    if(!defined('SSL_VERIFY_PEER')) define('SSL_VERIFY_PEER',true);
    $private=openssl_pkey_new(['private_key_bits'=>2048,'private_key_type'=>OPENSSL_KEYTYPE_RSA]);
    $details=openssl_pkey_get_details($private);openssl_pkey_export($private,$pem);
    $dir=sys_get_temp_dir().'/freeitsm-oidc-'.bin2hex(random_bytes(4));mkdir($dir,0700);
    file_put_contents($dir.'/jwks.json',json_encode(['keys'=>[['kty'=>'RSA','kid'=>'test','alg'=>'RS256','use'=>'sig','n'=>oidcBase64Url($details['rsa']['n']),'e'=>oidcBase64Url($details['rsa']['e'])]]]));
    $proc=proc_open(['php','-S','127.0.0.1:18087','-t',$dir],[0=>['pipe','r'],1=>['file',$dir.'/server.log','a'],2=>['file',$dir.'/server.log','a']],$pipes);
    try {
        $ready=false;for($i=0;$i<50;$i++){ $sock=@fsockopen('127.0.0.1',18087,$errno,$errstr,.1);if($sock){fclose($sock);$ready=true;break;}usleep(20000); }check($ready);
        $disco=['issuer'=>'https://identity.example.test','jwks_uri'=>'http://127.0.0.1:18087/jwks.json'];$provider=['client_id'=>'freeitsm'];
        $claims=['iss'=>$disco['issuer'],'aud'=>'freeitsm','sub'=>'existing-oidc-subject','nonce'=>'expected','iat'=>time(),'exp'=>time()+60];
        $token=\Firebase\JWT\JWT::encode($claims,$pem,'RS256','test');
        check(oidcValidateIdToken($token,$disco,$provider,'expected')['sub']==='existing-oidc-subject');
        foreach(['aud'=>'wrong','iss'=>'https://wrong.example.test','nonce'=>'wrong']as$key=>$value){$bad=$claims;$bad[$key]=$value;$rejected=false;try{oidcValidateIdToken(\Firebase\JWT\JWT::encode($bad,$pem,'RS256','test'),$disco,$provider,'expected');}catch(Exception $e){$rejected=true;}check($rejected,'OIDC must reject '.$key);}
    } finally {proc_terminate($proc);proc_close($proc);foreach(glob($dir.'/*')as$file)unlink($file);rmdir($dir);}
});

echo "$tests native LDAP and migration tests passed.\n";
