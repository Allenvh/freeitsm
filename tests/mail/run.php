<?php
namespace Webklex\PHPIMAP {
    final class ClientManager {
        public static array $configs=[]; public static int $closed=0; public static bool $fail=false;
        public function make(array $config) { self::$configs[]=$config; return new class {
            public function connect() { if (ClientManager::$fail) throw new \RuntimeException('server reflected '.end(ClientManager::$configs)['password']); }
            public function getFolder($name) { return $name==='missing' ? null : new class { public function examine() { return []; } }; }
            public function disconnect() { ClientManager::$closed++; }
        }; }
    }
}
namespace {
require_once __DIR__.'/../../includes/mail/MailboxProviderFactory.php';
require_once __DIR__.'/../../includes/encryption.php';
function check($condition,$message) { if(!$condition)throw new RuntimeException($message); }
$secret=bin2hex(random_bytes(24));$smtpSecret=bin2hex(random_bytes(24));
$mb=['provider'=>'imap_smtp','imap_host'=>'imap.example.test','imap_username'=>'helpdesk@freeitsm.test','imap_password_encrypted'=>$secret,'imap_port'=>3143,'imap_encryption'=>'none','imap_folder'=>'Original'];
$client=MailboxProviderFactory::imap($mb);
$folder=$client->verifyFolder('INBOX');$cfg=end(\Webklex\PHPIMAP\ClientManager::$configs);
check($folder['displayName']==='INBOX'&&$cfg['folder']==='INBOX','Requested folder must be checked');
check($cfg['password']===$secret&&$cfg['port']===3143&&$cfg['encryption']===false,'Configured basic auth/port/plaintext must be honored');
check(\Webklex\PHPIMAP\ClientManager::$closed===1,'Probe must close');
$failed=false;try {$client->verifyFolder('missing');}catch(RuntimeException $e){$failed=true;}check($failed,'Missing folder accepted');
\Webklex\PHPIMAP\ClientManager::$fail=true;
$failed=false;try {$client->test();}catch(RuntimeException $e){$failed=true;check(!str_contains($e->getMessage(),$secret),'Password leaked');}check($failed,'Bad auth accepted');
\Webklex\PHPIMAP\ClientManager::$fail=false;
check(!MailboxProviderFactory::isImapSmtp(['provider'=>'google'])&&!MailboxProviderFactory::isImapSmtp(['provider'=>'microsoft']),'OAuth routing changed');
$encrypted=encryptValue($secret);check($encrypted!==$secret&&decryptValue($encrypted)===$secret,'Encryption round trip');
echo "PASS IMAP basic configuration, folder selection/failure, cleanup, redaction, provider routing, encryption\n";
foreach(['auth-test','send','reject','relay']as$mode){
 $dir=sys_get_temp_dir().'/mail-test-'.bin2hex(random_bytes(5));mkdir($dir,0700);
 file_put_contents($dir.'/config',json_encode(['username'=>'smtp-user','password'=>$smtpSecret,'auth'=>$mode!=='relay']));
 $proc=proc_open(['php',__DIR__.'/smtp_fixture.php',$dir.'/config',$dir.'/ready',$dir.'/result'],[0=>['pipe','r'],1=>['file',$dir.'/log','a'],2=>['file',$dir.'/log','a']],$pipes);
 try {
  for($i=0;$i<100&&!file_exists($dir.'/ready');$i++)usleep(20000);
  $address=file_get_contents($dir.'/ready');$port=(int)substr($address,strrpos($address,':')+1);
  $smtp=['provider'=>'imap_smtp','outbound_enabled'=>true,'smtp_host'=>'127.0.0.1','smtp_port'=>$port,'smtp_encryption'=>'none','smtp_username'=>$mode==='relay'?'':'smtp-user','smtp_password_encrypted'=>$mode==='reject'?'invalid':$smtpSecret,'smtp_from_address'=>'helpdesk@freeitsm.test'];
  if($mode==='reject'){try{SmtpMailer::test($smtp);throw new LogicException('Invalid SMTP password accepted');}catch(Exception $e){check(str_contains($e->getMessage(),'535'),'Authentication failure not detected');}}
  elseif($mode==='send')SmtpMailer::send($smtp,'recipient@freeitsm.test','Fixture test','<p>Test</p>');
  else SmtpMailer::test($smtp);
  proc_close($proc);$proc=null;
  $result=json_decode(file_get_contents($dir.'/result'),true);
  check($result['authenticated']===($mode!=='reject'),'SMTP authentication result');check($result['received']===($mode==='send'),'Test must not send mail; send must deliver DATA');
  echo "PASS SMTP $mode without OAuth or IMAP credentials\n";
 }finally{if(is_resource($proc)){proc_terminate($proc);proc_close($proc);}foreach(glob($dir.'/*')as$f)unlink($f);rmdir($dir);}
}
}
