"""Disposable full-upgrade integration fixture; never point at a normal installation.
Requires freeitsm-upgrade-test on 127.0.0.1:8092, its freeitsm_upgrade_test
schema, and the freeitsm-cloud_default Docker network. The PHP bootstrap refuses
other database names. Credentials are generated per run and never printed.
"""
import subprocess,secrets,json,time,urllib.request,urllib.error,pathlib,tempfile,os
APP='freeitsm-upgrade-test';MAIL='freeitsm-upgrade-mail';BASE='http://127.0.0.1:8092'
def run(a):
 p=subprocess.run(a,capture_output=True,text=True)
 if p.returncode:
  msg=p.stderr+p.stdout
  for v in cfg.values():msg=msg.replace(v,'[redacted]')
  raise RuntimeError(msg)
 return p.stdout
cfg={k:secrets.token_hex(20) for k in ['read_password','smtp_password','user_password','csrf','session']}
cfg['subject']='Upgrade integration '+secrets.token_hex(6)
fd,name=tempfile.mkstemp(prefix='upgrade-greenmail-',suffix='.json');os.close(fd)
p=pathlib.Path(name);p.write_text(json.dumps(cfg));p.chmod(0o600)
created=False
if subprocess.run(['docker','inspect',MAIL],capture_output=True).returncode==0:raise RuntimeError('Fixture container already exists; refusing to replace it')
try:
 opts='-Dgreenmail.setup.test.smtp -Dgreenmail.setup.test.imap -Dgreenmail.hostname=0.0.0.0 -Dgreenmail.users.login=email -Dgreenmail.users=helpdesk:'+cfg['read_password']+'@freeitsm.test,smtp:'+cfg['smtp_password']+'@freeitsm.test,user1:'+cfg['user_password']+'@freeitsm.test'
 run(['docker','run','-d','--name',MAIL,'--network','freeitsm-cloud_default','-e','GREENMAIL_OPTS='+opts,'greenmail/standalone:2.1.15'])
 created=True
 run(['docker','cp',str(p),APP+':/tmp/live.json']);run(['docker','exec',APP,'chown','www-data:www-data','/tmp/live.json'])
 code='''require "config.php";require "includes/functions.php";require "includes/encryption.php";require "includes/mailbox_imap.php";if(DB_NAME!=="freeitsm_upgrade_test")exit(99);$c=json_decode(file_get_contents('/tmp/live.json'),true);$db=connectToDatabase();$m=['name'=>'Upgrade live mail','provider'=>'imap','provider_type'=>'imap_smtp','imap_server'=>'freeitsm-upgrade-mail','imap_port'=>3143,'imap_encryption'=>'none','imap_username'=>'helpdesk@freeitsm.test','imap_password'=>$c['read_password'],'smtp_server'=>'freeitsm-upgrade-mail','smtp_port'=>3025,'smtp_encryption'=>'none','smtp_username'=>'smtp@freeitsm.test','smtp_password'=>$c['smtp_password'],'target_mailbox'=>'helpdesk@freeitsm.test','email_folder'=>'INBOX','max_emails_per_check'=>10,'is_active'=>1,'imported_action'=>'mark_read','rejected_action'=>'mark_read','azure_tenant_id'=>'','azure_client_id'=>'','azure_client_secret'=>'','oauth_redirect_uri'=>''];$cols=array_keys($m);$vals=[];foreach($m as $k=>$v)$vals[]=in_array($k,ENCRYPTED_MAILBOX_COLUMNS,true)?encryptValue((string)$v):$v;$db->prepare('INSERT INTO target_mailboxes (`'.implode('`,`',$cols).'`) VALUES('.implode(',',array_fill(0,count($vals),'?')).')')->execute($vals);$id=(int)$db->lastInsertId();$admin=(int)$db->query("SELECT id FROM analysts WHERE is_admin=1 AND is_active=1 ORDER BY id LIMIT 1")->fetchColumn();$db->prepare('UPDATE analysts SET must_change_password=0,password_changed_datetime=UTC_TIMESTAMP() WHERE id=?')->execute([$admin]);session_id($c['session']);session_start();$_SESSION=['analyst_id'=>$admin,'analyst_name'=>'Upgrade test','csrf_token'=>$c['csrf']];session_write_close();$incoming=$m;$incoming['target_mailbox']='user1@freeitsm.test';imapSmtpSend($incoming,'helpdesk@freeitsm.test','',$c['subject'],'<p>Incoming upgrade test</p>');echo json_encode(['mailbox_id'=>$id]);'''
 for _ in range(30):
  probe=run(['docker','exec',APP,'php','-r',"$s=@fsockopen('"+MAIL+"',3025,$e,$m,1);echo $s?'ready':'wait';"])
  if probe.strip()=='ready':break
  time.sleep(.3)
 data=json.loads(run(['docker','exec','--user','www-data',APP,'php','-d','session.save_path=/tmp','-r',code]));mid=data['mailbox_id']
 def api(path,data):
  req=urllib.request.Request(BASE+path,data=json.dumps(data).encode(),headers={'Content-Type':'application/json','X-CSRF-Token':cfg['csrf'],'Cookie':'PHPSESSID='+cfg['session']})
  with urllib.request.build_opener(urllib.request.ProxyHandler({})).open(req,timeout=40) as r: return json.load(r)
 result=api('/api/tickets/verify_mailbox_folder.php',{'mailbox_id':mid,'folder_name':'INBOX'})
 assert result.get('success'),str(result)
 print('PASS real GreenMail IMAP folder verification through HTTP API, no OAuth',flush=True)
 result=api('/api/tickets/check_mailbox_email.php',{'mailbox_id':mid,'manual':True})
 assert result.get('success'),str(result)
 query="require 'config.php';require 'includes/functions.php';$c=json_decode(file_get_contents('/tmp/live.json'),true);$s=connectToDatabase()->prepare('SELECT id FROM tickets WHERE subject=?');$s->execute([$c['subject']]);echo json_encode($s->fetchAll(PDO::FETCH_COLUMN));"
 ids=json.loads(run(['docker','exec',APP,'php','-r',query]));assert len(ids)==1,str(result)
 print('PASS incoming GreenMail message creates exactly one ticket through HTTP API',flush=True)
 result=api('/api/tickets/send_email.php',{'ticket_id':ids[0],'to':'user1@freeitsm.test','subject':'Re: '+cfg['subject'],'body':'<p>Reply integration</p>','type':'reply'})
 assert result.get('success'),str(result)
 probe="require 'config.php';require 'includes/mailbox_imap.php';$c=json_decode(file_get_contents('/tmp/live.json'),true);$m=['imap_server'=>'"+MAIL+"','imap_port'=>3143,'imap_encryption'=>'none','imap_username'=>'user1@freeitsm.test','imap_password'=>$c['user_password'],'email_folder'=>'INBOX'];$s=imapStreamFor($m);echo json_encode(imap_search($s,'ALL'));"
 messages=json.loads(run(['docker','exec',APP,'php','-r',probe]));assert messages and len(messages)==1
 print('PASS ticket reply delivered through independent SMTP credentials and received via IMAP',flush=True)
 unread="require 'config.php';require 'includes/functions.php';require 'includes/encryption.php';require 'includes/mailbox_imap.php';$db=connectToDatabase();$m=decryptMailboxRow($db->query('SELECT * FROM target_mailboxes WHERE id="+str(mid)+"')->fetch(PDO::FETCH_ASSOC));$s=imapStreamFor($m);$uids=imap_search($s,'ALL',SE_UID);imap_clearflag_full($s,(string)$uids[0],'\\\\Seen',ST_UID);$key=$db->query('SELECT exchange_message_id FROM emails WHERE mailbox_id="+str(mid)+" ORDER BY id LIMIT 1')->fetchColumn();echo str_starts_with($key,'imap:"+str(mid)+":')?'legacy-key':'bad-key';"
 assert run(['docker','exec',APP,'php','-r',unread]).strip()=='legacy-key'
 api('/api/tickets/check_mailbox_email.php',{'mailbox_id':mid,'manual':True})
 ids=json.loads(run(['docker','exec',APP,'php','-r',query]));assert len(ids)==1
 print('PASS second mailbox check does not duplicate the ticket',flush=True)
finally:
 if created:subprocess.run(['docker','rm','-f',MAIL],capture_output=True)
 p.unlink(missing_ok=True)
