"""Credential round-trip regression for the isolated freeitsm_ad_test HTTP app."""
import http.cookiejar, urllib.request, urllib.parse, json, secrets, subprocess, hashlib, os
from pathlib import Path
base='http://127.0.0.1:8088'
def fixture(code):
    prefix='require "/var/www/html/config.php";if(DB_NAME!=="freeitsm_ad_test")exit(99);require_once "/var/www/html/includes/ad/bootstrap.php";$s=adService();'
    subprocess.run(['docker','exec','freeitsm-ad-http','php','-r',prefix+code],check=True,capture_output=True)
fixture('')
subprocess.run(['docker','cp',str(Path(__file__).with_name('imap_fixture.php')),'freeitsm-ad-http:/tmp/imap_fixture.php'],check=True,capture_output=True)
client=urllib.request.build_opener(urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()))
client.open(base+'/login.php',urllib.parse.urlencode({'username':os.environ['MAIL_TEST_ADMIN_USERNAME'],'password':os.environ['MAIL_TEST_ADMIN_PASSWORD']}).encode()).read()
def api(path,data=None):
    req=urllib.request.Request(base+'/api/tickets/'+path,data=json.dumps(data).encode() if data is not None else None,headers={'Content-Type':'application/json'})
    return json.load(client.open(req))
imap=secrets.token_hex(20);smtp=secrets.token_hex(20)
data={'name':'Credential regression','provider':'imap_smtp','provider_type':'imap_smtp','target_mailbox':'helpdesk-regression@freeitsm.test','imap_host':'127.0.0.1','imap_username':'helpdesk-regression@freeitsm.test','imap_password':imap,'imap_port':3143,'imap_encryption':'none','imap_folder':'INBOX','smtp_host':'127.0.0.1','smtp_port':3025,'smtp_encryption':'none','smtp_username':'independent-smtp-user','smtp_password':smtp,'smtp_from_address':'helpdesk-regression@freeitsm.test'}
id=None
try:
    result=api('save_mailbox.php',data);assert result['success'],result;id=int(result['id'])
    print('PASS create Generic IMAP/SMTP without OAuth redirect URI, client or scopes')
    data.update(id=id,imap_password='',smtp_password='')
    assert api('save_mailbox.php',data)['success']
    ih=hashlib.sha256(imap.encode()).hexdigest();sh=hashlib.sha256(smtp.encode()).hexdigest()
    fixture(f'$r=$s->query("SELECT * FROM target_mailboxes WHERE id=?",[{id}])->fetch(PDO::FETCH_ASSOC);if(!str_starts_with($r["imap_password_encrypted"],"ENC:")||!str_starts_with($r["smtp_password_encrypted"],"ENC:"))exit(2);if(hash("sha256",decryptValue($r["imap_password_encrypted"]))!=="{ih}"||hash("sha256",decryptValue($r["smtp_password_encrypted"]))!=="{sh}")exit(3);')
    print('PASS both passwords encrypted, independently retrieved and preserved on blank edit')
    listed=api('get_mailboxes.php');assert listed['success'];record=next(r for r in listed['mailboxes'] if int(r['id'])==id)
    assert 'imap_password_encrypted' not in record and 'smtp_password_encrypted' not in record
    assert imap not in json.dumps(listed) and smtp not in json.dumps(listed)
    print('PASS mailbox listing excludes passwords')
    for folder, accepted, expected_success in [('INBOX',imap,True),('missing',imap,False),('INBOX',secrets.token_hex(20),False)]:
        proc=subprocess.Popen(['docker','exec','-i','freeitsm-ad-http','php','/tmp/imap_fixture.php'],stdin=subprocess.PIPE,stdout=subprocess.PIPE,stderr=subprocess.PIPE,text=True)
        try:
            proc.stdin.write(json.dumps({'username':data['imap_username'],'password':accepted})+'\n');proc.stdin.flush()
            address=proc.stdout.readline().strip();assert ':' in address, 'IMAP fixture failed to start'
            data['imap_port']=int(address.rsplit(':',1)[1]);assert api('save_mailbox.php',data)['success']
            result=api('verify_mailbox_folder.php',{'mailbox_id':id,'folder_name':folder})
            assert result['success'] is expected_success, result
            if expected_success:assert result['folder']['displayName']=='INBOX'
            else:assert 'Mailbox is not authenticated' not in result['error'] and imap not in result['error']
            proc.wait(timeout=10)
            print('PASS real IMAP library and Verify Folder endpoint: '+('authenticated INBOX' if expected_success else 'missing folder/auth rejection'))
        finally:
            if proc.poll() is None:proc.terminate()
finally:
    if id is not None:fixture(f'$s->query("DELETE FROM target_mailboxes WHERE id=?",[{id}]);')
