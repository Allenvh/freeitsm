<?php
/** Run against a disposable MySQL database; see docs/active-directory.md. */
require_once __DIR__ . '/../../includes/ad/Admin.php';
require_once __DIR__ . '/../../includes/functions.php';
session_start();
$host = getenv('AD_TEST_DB_HOST') ?: 'db';
$name = 'freeitsm_ad_test';
$db = new PDO("mysql:host=$host;charset=utf8mb4", getenv('AD_TEST_DB_USER') ?: 'root', getenv('AD_TEST_DB_PASSWORD') ?: 'rootpassword', [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$db->exec("DROP DATABASE IF EXISTS $name"); $db->exec("CREATE DATABASE $name"); $db->exec("USE $name");
$db->exec(file_get_contents(__DIR__ . '/../../database/freeitsm.sql'));
$db->exec(file_get_contents(__DIR__ . '/../../database/migrations/20261103_active_directory.sql'));
$db->exec(file_get_contents(__DIR__ . '/../../database/migrations/20261103_active_directory.sql'));
final class FakeAd implements AdDirectory {
    public array $accounts = []; public array $memberships = []; public array $passwords = [];
    public array $binds = []; public array $finds = []; public array $fail = [];
    public function find(array $p,string $email): ?array { $this->finds[]=$p['id']; if(isset($this->fail['find'.$p['id']])) throw new AdFailure($this->fail['find'.$p['id']]); return $this->accounts[$p['id']][$email]??null; }
    public function authenticate(array $p,array $a,string $password): bool { $this->binds[]=$p['id']; if(isset($this->fail['bind'.$p['id']])) throw new AdFailure($this->fail['bind'.$p['id']]); return ($this->passwords[$p['id']]??null)===$password; }
    public function groups(array $p,array $a): array { if(isset($this->fail['groups'])) throw new AdFailure($this->fail['groups']); return $this->memberships[$p['id']]??[]; }
}
$fake = new FakeAd(); $s = new AdService($db,$fake); $passed=0;
function check(bool $v,string $why='assertion'): void { if(!$v) throw new RuntimeException($why); }
function failure(callable $f,string $reason): void { try { $f(); } catch(AdFailure $e) { check($e->reason===$reason,"Expected $reason; got {$e->reason}"); return; } throw new RuntimeException("Expected failure: $reason"); }
function test(string $name, callable $fn): void { global $passed,$s,$fake; $s->query('DELETE FROM ad_rate_limits'); $fake->binds=[]; $fake->finds=[]; $fake->fail=[]; $fn(); $passed++; echo "PASS $name\n"; }
function account(string $email,string $guid): array { return ['email'=>$email,'guid'=>str_pad($guid,32,'0',STR_PAD_LEFT),'dn'=>'CN=Alice,DC=example,DC=test','username'=>'alice','name'=>'Alice Example','disabled'=>false,'locked'=>false,'expired'=>false]; }
function profile(string $email): int { global $s; $s->query('INSERT INTO ad_profiles (email,verified_at) VALUES (?,UTC_TIMESTAMP())',[$email]);return (int)$s->db->lastInsertId(); }
$providerConfig = ['domain_dns'=>'corp.example.test','domain_suffix'=>'corp.example.test','primary'=>'dc.corp.example.test','secondary'=>'','base_dn'=>'DC=corp,DC=example,DC=test','user_base'=>'','group_base'=>'','bind_identity'=>'svc@corp.example.test','transport'=>'ldaps','port'=>636,'timeout'=>2,'username_attribute'=>'sAMAccountName','email_attribute'=>'mail','display_attribute'=>'displayName','nested_groups'=>true,'ca_file'=>''];
foreach ([1,2] as $id) $s->query('INSERT INTO ad_providers (id,name,enabled,trusted,authentication_enabled,priority,config_json,password_encrypted) VALUES (?, ?, 1, 1, 1, ?, ?, ?)',[$id,'Domain '.$id,$id,json_encode($providerConfig),encryptValue('test-service-secret')]);
$s->query("UPDATE ad_settings SET enabled=1,authority_id=1,default_provider_id=1,allowed_domains='[\"example.test\"]',base_url='https://itsm.example.test' WHERE id=1");
$support=(int)$s->query("SELECT id FROM access_groups WHERE group_key='support'")->fetchColumn();
$admin=(int)$s->query("SELECT id FROM access_groups WHERE group_key='admin'")->fetchColumn();
$s->query("INSERT INTO access_group_modules(access_group_id,module_key) VALUES (?,'tickets'),(?,'system')",[$support,$admin]);
$groupGuid=str_repeat('a',32);
$s->query('INSERT INTO ad_group_mappings(provider_id,group_guid,group_name,access_group_id,precedence) VALUES(1,?,\'Support\',?,100)',[$groupGuid,$support]);
$email='alice@example.test'; $pid=0;
$fake->accounts=[1=>[$email=>account($email,'1')],2=>[$email=>account($email,'2')]];
$fake->passwords=[1=>'domain-one-password',2=>'domain-two-password'];
$fake->memberships=[1=>[['guid'=>$groupGuid,'name'=>'Nested Support']],2=>[['guid'=>str_repeat('b',32),'name'=>'Untrusted Admin']]];

test('migration repeatability and local password compatibility',function()use($s){$hash=$s->query("SELECT password_hash FROM analysts WHERE username='admin'")->fetchColumn();check(password_verify('freeitsm',$hash));check($s->query('SELECT COUNT(*) FROM ad_settings')->fetchColumn()==1);});
test('verification creates a pending profile without a session or requester',function()use($s,&$pid,$email){$link='';$s->requestVerification(strtoupper($email),function($to,$url)use(&$link){$link=$url;});parse_str(parse_url($link,PHP_URL_QUERY),$q);$token=$q['verify'];check(!$s->query('SELECT id FROM ad_profiles WHERE email=?',[$email])->fetchColumn());check(!$s->query('SELECT 1 FROM ad_verifications WHERE token_hash=?',[$token])->fetchColumn());$s->verify($token);$p=$s->query('SELECT * FROM ad_profiles WHERE email=?',[$email])->fetch(PDO::FETCH_ASSOC);$pid=(int)$p['id'];check(!$p['user_id']&&!$p['analyst_id']);check(empty($_SESSION['ad_profile_id']));failure(fn()=>$s->verify($token),'verification_invalid');});
test('expired verification cannot be consumed',function()use($s){$t=bin2hex(random_bytes(32));$s->query('INSERT INTO ad_verifications(token_hash,email,expires_at)VALUES(?,?,UTC_TIMESTAMP()-INTERVAL 1 SECOND)',[hash('sha256',$t),'expired@example.test']);failure(fn()=>$s->verify($t),'verification_invalid');});
test('unknown domains and existing accounts do not receive registration mail',function()use($s){$sent=0;foreach(['x@other.test','admin@localhost','alice@example.test']as$e)$s->requestVerification($e,function()use(&$sent){$sent++;});check($sent===0);});
test('resend invalidates all sibling tokens on consumption',function()use($s){$tokens=[];for($i=0;$i<2;$i++)$s->requestVerification('resend@example.test',function($to,$url)use(&$tokens){parse_str(parse_url($url,PHP_URL_QUERY),$q);$tokens[]=$q['verify'];});$s->verify($tokens[1]);failure(fn()=>$s->verify($tokens[0]),'verification_invalid');});
test('single-domain login creates one requester and explicit Support permissions',function()use($s,$fake,$email,&$pid){$p=$s->login($email,'domain-one-password');check((int)$p['id']===$pid&&$p['user_id']&&$p['analyst_id']);check($fake->binds===[1]);$state=json_decode($p['authorization_json'],true);check($state['modules']===['tickets']);check(getAnalystAllowedModules($s->db,$p['analyst_id'])===['tickets']);check($s->query('SELECT COUNT(*) FROM ad_identities WHERE profile_id=?',[$pid])->fetchColumn()==1);});
test('domain two is ignored in single-domain mode',function()use($s,$fake,$email){failure(fn()=>$s->login($email,'domain-two-password',2),'invalid_credentials');check($fake->binds===[]);});
test('optional dual-domain fallback shares the same internal profile and records both GUIDs',function()use($s,$fake,$email,$pid){$s->query('UPDATE ad_settings SET multi_domain=1,allow_fallback=1,max_attempts=2 WHERE id=1');$before=$s->profile($pid);$p=$s->login($email,'domain-two-password');check((int)$p['id']===$pid&&$before['user_id']===$p['user_id']&&$before['analyst_id']===$p['analyst_id']);check($fake->binds===[1,2]);check($s->query('SELECT COUNT(*) FROM ad_identities WHERE profile_id=?',[$pid])->fetchColumn()==2);check(json_decode($p['authorization_json'],true)['modules']===['tickets']);});
test('ticket ownership and assignments survive authentication through either domain',function()use($s,$pid,$email){
    $p=$s->profile($pid);$s->query("INSERT INTO tickets(ticket_number,subject,user_id,assigned_analyst_id)VALUES('AD-TEST-1','Historical ticket',?,?)",[$p['user_id'],$p['analyst_id']]);
    $again=$s->login($email,'domain-one-password');$ticket=$s->query("SELECT user_id,assigned_analyst_id FROM tickets WHERE ticket_number='AD-TEST-1'")->fetch(PDO::FETCH_ASSOC);check($ticket['user_id']===$again['user_id']&&$ticket['assigned_analyst_id']===$again['analyst_id']);
    $s->query('UPDATE ad_profiles SET last_provider_id=2 WHERE id=?',[$pid]);
});
test('non-authority group mappings cannot grant privileges even if stored in the database',function()use($s,$fake,$pid,$admin){
    $s->query("INSERT INTO ad_group_mappings(provider_id,group_guid,group_name,access_group_id)VALUES(2,?,'Other-domain Admin',?)",[str_repeat('b',32),$admin]);$p=$s->refresh($pid);check(json_decode($p['authorization_json'],true)['modules']===['tickets']);
});
test('previous successful provider narrows the next bind',function()use($s,$fake,$email){$s->login($email,'domain-two-password');check($fake->binds===[2]);});
test('fallback off caps password attempts at one',function()use($s,$fake,$email){$s->query('UPDATE ad_settings SET allow_fallback=0 WHERE id=1');failure(fn()=>$s->login($email,'domain-one-password'),'invalid_credentials');check($fake->binds===[2]);$s->query('UPDATE ad_settings SET allow_fallback=1 WHERE id=1');});
test('invalid credentials never authenticate or fall back locally',function()use($s,$fake,$email){failure(fn()=>$s->login($email,'wrong'),'invalid_credentials');check(count($fake->binds)===2);});
test('unknown user does not bind',function()use($s,$fake){failure(fn()=>$s->login('unknown@example.test','password'),'invalid_credentials');check(!$fake->binds);});
foreach(['disabled','locked','expired']as$flag)test("$flag directory accounts are rejected before password bind",function()use($s,$fake,$email,$flag){$fake->accounts[1][$email][$flag]=true;failure(fn()=>$s->login($email,'domain-two-password'),'account_unavailable');check(!$fake->binds);$fake->accounts[1][$email][$flag]=false;});
test('directory ambiguity cannot be bypassed by selecting another domain',function()use($s,$fake,$email){$fake->fail['find1']='ambiguous_identity';failure(fn()=>$s->login($email,'domain-two-password',2),'ambiguous_identity');check(!$fake->binds);});
test('GUID reassignment is rejected even with the same verified email',function()use($s,$fake,$email){$old=$fake->accounts[1][$email]['guid'];$fake->accounts[1][$email]['guid']=str_repeat('f',32);failure(fn()=>$s->login($email,'domain-two-password',2),'identity_review_required');check(!$fake->binds);$fake->accounts[1][$email]['guid']=$old;});
test('trusted provider requirement and disabled providers exclude binds',function()use($s,$fake,$email){$s->query('UPDATE ad_providers SET trusted=0 WHERE id=2');failure(fn()=>$s->login($email,'domain-two-password',2),'invalid_credentials');check(!$fake->binds);$s->query('UPDATE ad_providers SET trusted=1 WHERE id=2');});
test('login rate limits cannot be bypassed with new sessions',function()use($s,$email){for($i=0;$i<5;$i++)failure(fn()=>$s->login($email,'wrong'),'invalid_credentials');failure(fn()=>$s->login($email,'domain-one-password'),'rate_limited');});
test('authorization outage preserves last-known grants but effective grants fail closed',function()use($s,$fake,$pid){$before=$s->profile($pid);$fake->fail['groups']='partial_group_resolution';failure(fn()=>$s->refresh($pid),'partial_group_resolution');$after=$s->profile($pid);check($before['authorization_json']===$after['authorization_json']);check(getAnalystAllowedModules($s->db,$after['analyst_id'])===[]);unset($fake->fail['groups']);$s->refresh($pid);});
test('group removal updates persistent and session permissions to Guest',function()use($s,$fake,$pid){$fake->memberships[1]=[];$p=$s->refresh($pid);$s->applySession($p);check(empty($_SESSION['analyst_id'])&&isset($_SESSION['ss_user_id']));check(json_decode($p['authorization_json'],true)['role']==='Guest');check(getAnalystAllowedModules($s->db,$p['analyst_id'])===[]);});
test('group addition updates the existing analyst and requester session',function()use($s,$fake,$pid,$groupGuid){$fake->memberships[1]=[['guid'=>$groupGuid,'name'=>'Nested Support']];$p=$s->refresh($pid);$s->applySession($p);check((int)$_SESSION['analyst_id']===(int)$p['analyst_id']&&$_SESSION['allowed_modules']===['tickets']);});
test('multiple authoritative groups combine modules and apply role precedence',function()use($s,$fake,$pid,$admin){$g=str_repeat('c',32);$s->query('INSERT INTO ad_group_mappings(provider_id,group_guid,group_name,access_group_id,precedence)VALUES(1,?,\'Admin\',?,1)',[$g,$admin]);$fake->memberships[1][]=['guid'=>$g,'name'=>'Admin'];$p=$s->refresh($pid);$st=json_decode($p['authorization_json'],true);check($st['role']==='FreeITSM Admin'&&in_array('system',$st['modules'],true));array_pop($fake->memberships[1]);$s->refresh($pid);});
test('a confirmed missing authority account gets Guest; a timeout does not',function()use($s,$fake){$email='noauthority@example.test';$id=profile($email);$fake->accounts[2][$email]=account($email,'3');$p=$s->login($email,'domain-two-password');check(json_decode($p['authorization_json'],true)['role']==='Guest');$fake->fail['find1']='directory_timeout';failure(fn()=>$s->refresh($id),'directory_timeout');check($s->profile($id)['authorization_status']==='directory_timeout');});
test('existing requester is not automatically claimed by a verified mailbox',function()use($s,$fake){$email='legacy@example.test';profile($email);$s->query('INSERT INTO users(email,display_name)VALUES(?,\'Historical owner\')',[$email]);$fake->accounts[1][$email]=account($email,'4');failure(fn()=>$s->login($email,'domain-one-password'),'identity_review_required');check($s->query('SELECT COUNT(*) FROM users WHERE email=?',[$email])->fetchColumn()==1);});
test('manual and administrative refresh limits are persistent',function()use($s){$s->limit('manual_refresh','test',1,30);failure(fn()=>$s->limit('manual_refresh','test',1,30),'rate_limited');});
test('AD enrollment blocks local password and legacy module edits',function()use($s,$pid){$p=$s->profile($pid);check(adManaged($s->db,'analyst_id',$p['analyst_id']));failure(fn()=>adProtectAnalyst($s->db,(int)$p['analyst_id']),'Manage AD identities in Active Directory administration.');});
test('provider validation prevents plaintext transport and attribute injection',function()use($s,$providerConfig){$a=new AdAdmin($s);$d=$providerConfig+['name'=>'Unsafe','service_password'=>'test'];$d['transport']='ldap';failure(fn()=>$a->saveProvider($d),'encrypted_ldap_required');$d['transport']='ldaps';$d['email_attribute']='mail)(objectClass=*';failure(fn()=>$a->saveProvider($d),'invalid_attribute');});
test('mapping writes reject a non-authoritative domain',function()use($s){failure(fn()=>(new AdAdmin($s))->mapping(['provider_id'=>2,'group_guid'=>str_repeat('b',32)]),'mappings_require_authority');});
test('periodic session refresh runs after the configured interval',function()use($s,$fake,$pid){
    $s->query("UPDATE ad_profiles SET refreshed_at=UTC_TIMESTAMP()-INTERVAL 901 SECOND WHERE id=?",[$pid]);
    $before=count($fake->finds);$p=$s->sessionProfile($pid);check(count($fake->finds)>$before);check($_SESSION['allowed_modules']===['tickets']);
});
test('fresh sessions reuse grants but sensitive operations require recent directory lookup',function()use($s,$fake,$pid){
    $s->query("UPDATE ad_profiles SET refreshed_at=UTC_TIMESTAMP()-INTERVAL 61 SECOND WHERE id=?",[$pid]);
    $before=count($fake->finds);$s->sessionProfile($pid);check(count($fake->finds)===$before);$s->sessionProfile($pid,true);check(count($fake->finds)>$before);
});
test('sensitive refresh failure removes effective grants without rewriting persisted roles',function()use($s,$fake,$pid){
    $s->query("UPDATE ad_profiles SET refreshed_at=UTC_TIMESTAMP()-INTERVAL 61 SECOND WHERE id=?",[$pid]);$fake->fail['find1']='tls_failure';failure(fn()=>$s->sessionProfile($pid,true),'tls_failure');check(getAnalystAllowedModules($s->db,$s->profile($pid)['analyst_id'])===[]);unset($fake->fail['find1']);$s->refresh($pid);
});
test('configuration changes invalidate otherwise fresh authorization',function()use($s,$fake,$pid){
    $s->query('UPDATE ad_settings SET revision=revision+1 WHERE id=1');$before=count($fake->finds);$s->sessionProfile($pid);check(count($fake->finds)>$before);
});
test('registration limits prevent an unbounded resend queue',function()use($s){$sent=0;for($i=0;$i<3;$i++)$s->requestVerification('rate@example.test',function()use(&$sent){$sent++;});failure(fn()=>$s->requestVerification('rate@example.test',fn()=>null),'rate_limited');check($sent===3);});
test('duplicate directory email resolution fails closed',function()use($s,$fake,$email){$fake->fail['find1']='ambiguous_identity';failure(fn()=>$s->login($email,'domain-one-password'),'ambiguous_identity');check(!$fake->binds);});
test('an empty mapped role cannot become unrestricted analyst access',function()use($s,$fake,$support,$pid){$s->query('DELETE FROM access_group_modules WHERE access_group_id=?',[$support]);$p=$s->refresh($pid);$s->applySession($p);check(!isset($_SESSION['analyst_id']));$s->query("INSERT INTO access_group_modules(access_group_id,module_key)VALUES(?,'tickets')",[$support]);$s->refresh($pid);});
test('controller errors stop password fallback rather than becoming invalid credentials',function()use($s,$fake,$email){$fake->fail['bind2']='user_bind_unavailable';failure(fn()=>$s->login($email,'domain-one-password'),'user_bind_unavailable');check($fake->binds===[2]);});
test('encrypted service passwords are not plaintext database values',function()use($s){$p=$s->providers()[0];check(str_starts_with($p['password_encrypted'],'ENC:'));check($p['password_encrypted']!=='test-service-secret');check(decryptValue($p['password_encrypted'])==='test-service-secret');});
test('enabling one domain verifies and protects a working local emergency administrator',function()use($s){
    $s->query("INSERT INTO target_mailboxes(name,azure_tenant_id,azure_client_id,azure_client_secret,oauth_redirect_uri,imap_server,target_mailbox,smtp_host,smtp_from_address,smtp_encryption)VALUES('Test SMTP','','','','','','noreply@example.test','smtp.example.test','noreply@example.test','tls')");$mail=(int)$s->db->lastInsertId();
    $emergency=(int)$s->query("SELECT id FROM analysts WHERE username='admin'")->fetchColumn();
    $d=['enabled'=>1,'authority_id'=>1,'default_provider_id'=>1,'allowed_domains'=>'example.test','base_url'=>'https://itsm.example.test','mailbox_id'=>$mail,'emergency_analyst_id'=>$emergency,'emergency_password'=>'wrong'];
    $admin=new AdAdmin($s);failure(fn()=>$admin->saveSettings($d),'verify_emergency_local_administrator_password');
    $d['emergency_password']='freeitsm';$admin->saveSettings($d);$cfg=$s->settings();check(!$cfg['multi_domain']&&$cfg['max_attempts']==1&&$cfg['authority_id']==$cfg['default_provider_id']);
    failure(fn()=>adProtectAnalyst($s->db,$emergency),'Protected emergency administrator. Reassign it in AD settings first.');
    $s->query('UPDATE ad_settings SET multi_domain=1,allow_fallback=1,max_attempts=2 WHERE id=1');
});
test('CSRF and admin diagnostics deny unauthorized callers',function()use($s){$_SESSION=[];$_SERVER['REQUEST_METHOD']='POST';$_POST=['csrf'=>'invalid'];failure(fn()=>adCheckCsrf(),'csrf_failed');failure(fn()=>adRequireAdmin($s),'administrator_required');});
test('TLS connection errors do not turn into an empty group set',function()use($providerConfig){$config=$providerConfig;$config['primary']='127.0.0.1';$config['port']=1;$config['timeout']=1;$p=['config'=>$config,'password_encrypted'=>encryptValue('test')];try{(new LdapAdDirectory())->find($p,'alice@example.test');throw new RuntimeException('Expected connection failure');}catch(AdFailure $e){check(in_array($e->reason,['tls_or_connection_failure','directory_unavailable'],true));}});

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

echo "$passed tests passed; dedicated MySQL schema and mocked directory.\n";
