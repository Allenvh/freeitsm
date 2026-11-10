<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
/** Disposable-database integration: native dates, atomic ledger/files, privacy, normal creates. */
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../includes/functions.php';
require_once __DIR__.'/../includes/sharepoint_import.php';
require_once __DIR__.'/../includes/portal_visibility.php';
require_once __DIR__.'/../includes/search/indexer.php';
$db=connectToDatabase();
if (!getenv('FREEITSM_TEST_DATABASE') || getenv('FREEITSM_TEST_DATABASE')!==$db->query('SELECT DATABASE()')->fetchColumn()) { fwrite(STDERR,"Use an explicitly named disposable database.\n");exit(2); }
function check($condition,string $message):void { if(!$condition)throw new RuntimeException($message);echo "PASS $message\n"; }
function reject(callable $fn,string $message):void { try{$fn();}catch(Throwable $e){check(true,$message);return;}throw new RuntimeException($message); }
$vector=json_decode(file_get_contents(__DIR__.'/sharepoint-export/fingerprint.json'),true,512,JSON_THROW_ON_ERROR);
check(hash_equals($vector['fingerprint'],sharePointImportFingerprint($vector)),'Python/PHP manifest fingerprints agree for Unicode, slashes and newlines');
$tag=bin2hex(random_bytes(5));
$db->prepare('INSERT INTO analysts(username,password_hash,full_name,email,is_active,is_admin,can_access_all_modules) VALUES(?,?,?,?,1,1,1)')->execute(['zzsp-'.$tag,password_hash(bin2hex(random_bytes(24)),PASSWORD_DEFAULT),'Import admin','admin-'.$tag.'@example.test']);$actor=(int)$db->lastInsertId();
$db->prepare('INSERT INTO analysts(username,password_hash,full_name,email,is_active,is_admin,can_access_all_modules) VALUES(?,?,?,?,1,0,0)')->execute(['zzsp-plain-'.$tag,password_hash(bin2hex(random_bytes(24)),PASSWORD_DEFAULT),'Plain analyst','plain-'.$tag.'@example.test']);$plain=(int)$db->lastInsertId();
$tenant=(int)$db->query('SELECT id FROM tenants WHERE is_default=1 LIMIT 1')->fetchColumn();
$closed=(int)$db->query('SELECT id FROM ticket_statuses WHERE is_closed=1 ORDER BY id LIMIT 1')->fetchColumn();
$db->exec("INSERT INTO workflows(name,trigger_event,conditions,actions,is_active) VALUES('zzsp-normal-create','ticket.created','[]','[]',1)");$workflow=(int)$db->lastInsertId();
$root=sys_get_temp_dir().'/sharepoint-history-'.$tag;mkdir($root,0700);
$source='https://example.sharepoint.com/Lists/Issues#'.$tag;
$year=(int)gmdate('Y')-1;$created="$year-01-02 10:00:00";$modified="$year-01-03 12:00:00";
file_put_contents($root.'/report.txt',"Original associated report\n");
$file=['path'=>'report.txt','name'=>'report.txt','size'=>filesize($root.'/report.txt'),'sha256'=>hash_file('sha256',$root.'/report.txt')];
$item=['source_id'=>'101','source_fields'=>['Resolution'=>'Private resolution','Sign-off status'=>'Signed off'],
    'payload'=>['subject'=>'ZZSP history '.$tag,'description'=>'Original requester description','requester_email'=>'requester-'.$tag.'@example.test','requester_name'=>'Original Requester','company_id'=>$tenant,'assigned_analyst_id'=>$actor,'status_id'=>$closed],
    'history'=>['created_at'=>$created,'modified_at'=>$modified,'internal_context'=>'Original sign-off: Signed off; private resolution',
        'comments'=>[['text'=>'Historical private comment','created_at'=>"$year-01-02 11:00:00",'author_email'=>'old-person@example.test','author_name'=>'Original Person','source_comment_id'=>'7']], 'files'=>[$file]]];
$item['fingerprint']=sharePointImportFingerprint($item);
$manifest=$root.'/plan.json';
$write=function(array $rows)use($source,$manifest){file_put_contents($manifest,json_encode(['format'=>'freeitsm-sharepoint-history-v1','source'=>$source,'tickets'=>$rows],JSON_THROW_ON_ERROR));};
$write([$item]);
$hadLedger=sharePointImportLedgerReady($db);$beforeTickets=(int)$db->query('SELECT COUNT(*) FROM tickets')->fetchColumn();
$plan=sharePointImportLoad($db,$manifest,$actor);
check(sharePointImportLedgerReady($db)===$hadLedger && (int)$db->query('SELECT COUNT(*) FROM tickets')->fetchColumn()===$beforeTickets && $plan['tickets'][0]['action']==='create','preview changes no schema or ticket data');
reject(fn()=>sharePointImportLoad($db,$manifest,$plain),'non-admin cannot use historical import');
reject(fn()=>TicketsService::createTicket($db,new ActorContext($plain,null,'api'),$tenant,$item['payload'],null,'test',null,$item['history']),'service refuses historical dates for a non-admin');
$result=sharePointImportApply($db,$plan,$actor);$ticket=(int)$result[0]['ticket_id'];
$q=$db->prepare('SELECT * FROM tickets WHERE id=?');$q->execute([$ticket]);$stored=$q->fetch(PDO::FETCH_ASSOC);
check($stored['created_datetime']===$created && $stored['updated_datetime']===$modified,'native creation and modification dates preserved');
check($stored['closed_datetime']===null,'missing original closure time stays unknown');
check((int)$stored['status_id']===$closed,'closed source status is retained without fabricating closure date');
check((int)$stored['assigned_analyst_id']===$actor && (int)$stored['owner_id']===$actor,'analyst and owner assignments preserved');
check((int)$db->query("SELECT run_count FROM workflows WHERE id=$workflow")->fetchColumn()===0,'historical import sends no ticket-created workflow');
$q=$db->prepare('SELECT e.received_datetime FROM emails e WHERE ticket_id=? AND is_initial=1');$q->execute([$ticket]);
check($q->fetchColumn()===$created,'opening message uses the source creation timestamp');
$q=$db->prepare('SELECT note_text,is_internal,created_datetime FROM ticket_notes WHERE ticket_id=? ORDER BY id');$q->execute([$ticket]);$notes=$q->fetchAll(PDO::FETCH_ASSOC);
check(count($notes)===2 && (int)$notes[0]['is_internal']===1 && (int)$notes[1]['is_internal']===1,'context and imported comments are internal notes');
check($notes[1]['created_datetime']==="$year-01-02 11:00:00" && str_contains($notes[1]['note_text'],'Original Person'),'comment timestamp and original-author attribution retained');
$q=$db->prepare('SELECT a.*,e.direction FROM email_attachments a JOIN emails e ON e.id=a.email_id WHERE e.ticket_id=?');$q->execute([$ticket]);$attachment=$q->fetch(PDO::FETCH_ASSOC);
$storedPath=dirname(__DIR__).'/tickets/attachments/'.$attachment['file_path'];
check(hash_file('sha256',$storedPath)===$file['sha256'],'associated file copied byte-for-byte into native attachment storage');
foreach ([PORTAL_THIRD_PARTY_SHOW,PORTAL_THIRD_PARTY_NO_ATTACHMENTS,PORTAL_THIRD_PARTY_HIDE] as $policy) {
    $visibility=portalEmailVisibility(['direction'=>$attachment['direction']],'requester@example.test',$policy);
    check(!$visibility['visible'] && !$visibility['attachments'],'historical file hidden from portal under '.$policy);
}
// Drive the real download endpoint, with an ordinary visible file as the control.
$db->prepare('INSERT INTO email_attachments(email_id,filename,content_type,file_path,file_size,is_inline) VALUES(?,?,?,?,?,0)')->execute([
    (int)$db->query('SELECT id FROM emails WHERE ticket_id='.$ticket.' AND is_initial=1 ORDER BY id LIMIT 1')->fetchColumn(),
    'public-control.txt',$attachment['content_type'],$attachment['file_path'],$attachment['file_size']]);
$publicAttachment=(int)$db->lastInsertId();
$oldPolicy=$db->query("SELECT setting_value FROM system_settings WHERE setting_key='portal_third_party_visibility'")->fetchColumn();
$db->exec("INSERT INTO system_settings(setting_key,setting_value) VALUES('portal_third_party_visibility','show') ON DUPLICATE KEY UPDATE setting_value='show'");
$sid='zzsphistory'.bin2hex(random_bytes(12));$sessionPath=sys_get_temp_dir().'/sess_'.$sid;
file_put_contents($sessionPath,'ss_user_id|i:'.(int)$stored['user_id'].';ss_user_name|s:4:"Test";');
$socket=stream_socket_server('tcp://127.0.0.1:0');$address=stream_socket_get_name($socket,false);fclose($socket);
$port=(int)substr($address,strrpos($address,':')+1);$serverLog=$root.'/server.log';
$server=proc_open([PHP_BINARY,'-d','session.save_path='.sys_get_temp_dir(),'-S','127.0.0.1:'.$port,'-t',dirname(__DIR__)],[0=>['pipe','r'],1=>['file',$serverLog,'a'],2=>['file',$serverLog,'a']],$pipes);
try {
    $get=function(string $path,bool $post=false)use($port,$sid){$ch=curl_init('http://127.0.0.1:'.$port.$path);curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_COOKIE=>'PHPSESSID='.$sid,CURLOPT_TIMEOUT=>5,CURLOPT_POST=>$post]);$body=curl_exec($ch);$code=curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);return[$code,$body];};
    for($attempt=0;$attempt<40;$attempt++){[$code,$body]=$get('/api/self-service/get_attachment.php?id='.$publicAttachment);if($code)break;usleep(50000);}
    check($code===200 && $body===file_get_contents($storedPath),'HTTP control: requester can download an ordinary attachment');
    [$code]=$get('/api/self-service/get_attachment.php?id='.$attachment['id']);
    check($code===404,'HTTP: historical attachment refused to its requester even with show policy');
    [$code]=$get('/scripts/import_sharepoint_history.php',true);
    check($code===404,'HTTP: maintenance importer cannot run over the web');
} finally {
    if(is_resource($server)){proc_terminate($server);proc_close($server);}
    @unlink($sessionPath);
    $db->prepare('DELETE FROM email_attachments WHERE id=?')->execute([$publicAttachment]);
    if($oldPolicy===false)$db->exec("DELETE FROM system_settings WHERE setting_key='portal_third_party_visibility'");
    else $db->prepare("UPDATE system_settings SET setting_value=? WHERE setting_key='portal_third_party_visibility'")->execute([$oldPolicy]);
}
searchIndexTicket($db,$ticket);
$q=$db->prepare("SELECT COUNT(*) FROM search_documents WHERE ticket_id=? AND source_type IN ('email','attachment','note') AND is_internal=1");$q->execute([$ticket]);
check((int)$q->fetchColumn()>=3,'historical messages, comments and attachment text keep staff-only search scope');
$q=$db->prepare("SELECT is_internal FROM search_documents WHERE source_type='attachment' AND source_id=?");$q->execute([$attachment['id']]);
check((int)$q->fetchColumn()===1,'attachment text is explicitly internal in the search corpus');
$again=sharePointImportApply($db,sharePointImportLoad($db,$manifest,$actor),$actor);
check($again[0]['action']==='skipped' && (int)$again[0]['ticket_id']===$ticket,'rerun uses server ledger and does not duplicate ticket');
$changed=$item;$changed['payload']['subject']='Changed after import';$changed['fingerprint']=sharePointImportFingerprint($changed);$write([$changed]);
reject(fn()=>sharePointImportLoad($db,$manifest,$actor),'changed source record is refused');
$tampered=$item;$tampered['payload']['description']='Tampered without regenerating';$write([$tampered]);
reject(fn()=>sharePointImportLoad($db,$manifest,$actor),'prepared manifest tampering is refused');
$write([$item]);
$before=(int)$db->query("SELECT COUNT(*) FROM workflows WHERE id=$workflow AND run_count>0")->fetchColumn();
$normal=TicketsService::createTicket($db,new ActorContext($actor,null,'api'),$tenant,
    ['subject'=>'ZZSP normal '.$tag,'requester_email'=>'normal-'.$tag.'@example.test','history'=>$item['history'],'created_at'=>$created],null,'Normal API control');
$normalDate=$db->query('SELECT created_datetime FROM tickets WHERE id='.(int)$normal)->fetchColumn();
check($normalDate!==$created,'normal creates ignore historical values in the input body');
check((int)$db->query("SELECT run_count FROM workflows WHERE id=$workflow")->fetchColumn()>$before,'normal create still dispatches ticket-created workflow');

// Fail AFTER storing one file: prove database AND newly written files roll back.
file_put_contents($root.'/second.txt',"Second file\n");
$second=['path'=>'second.txt','name'=>'second.txt','size'=>filesize($root.'/second.txt'),'sha256'=>hash_file('sha256',$root.'/second.txt')];
$bad=$item;$bad['source_id']='102';$bad['payload']['subject']='ZZSP rollback '.$tag;$bad['history']['files'][]=$second;$bad['fingerprint']=sharePointImportFingerprint($bad);$write([$bad]);
$badPlan=sharePointImportLoad($db,$manifest,$actor);unlink($root.'/second.txt');
$payloadFiles=function(){
    $result=[];$base=dirname(__DIR__).'/tickets/attachments';
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base,FilesystemIterator::SKIP_DOTS)) as $file){
        if($file->isFile() && preg_match('/^[a-f0-9]{32}\./',$file->getFilename()))$result[]=$file->getPathname();
    }
    sort($result);return$result;
};
$filesBefore=$payloadFiles();
reject(fn()=>sharePointImportApply($db,$badPlan,$actor),'file failure rolls back whole source ticket');
$q=$db->prepare('SELECT COUNT(*) FROM tickets WHERE subject=?');$q->execute([$bad['payload']['subject']]);
check((int)$q->fetchColumn()===0 && sharePointImportExisting($db,$source,'102')===null,'failed ticket, emails and source ledger are not committed');
$orphans=$db->query("SELECT COUNT(*) FROM email_attachments a LEFT JOIN emails e ON e.id=a.email_id WHERE e.id IS NULL")->fetchColumn();
check((int)$orphans===0,'no orphan attachment rows after rollback');
check($payloadFiles()===$filesBefore,'newly stored file bytes are removed on rollback');
$future=$item;$future['source_id']='103';$future['history']['modified_at']='2999-01-01 00:00:00';$future['fingerprint']=sharePointImportFingerprint($future);$write([$future]);
reject(fn()=>sharePointImportLoad($db,$manifest,$actor),'future source dates are refused before importing');
foreach(glob($root.'/*')as$path)unlink($path);rmdir($root);
echo "Historical import integration passed. Fixture rows remain only in the disposable database.\n";
