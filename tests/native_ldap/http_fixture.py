"""Disposable native LDAP wire/HTTP integration. Requires Docker and tests/run.php schema."""
import contextlib, http.cookiejar, io, json, os, re, secrets, subprocess, tarfile, tempfile, time, urllib.parse, urllib.request
from pathlib import Path
from html.parser import HTMLParser
REPO=Path(__file__).resolve().parents[2]
LDAP='freeitsm-native-ldap-fixture'
APP='freeitsm-native-ldap-http'
BASE='http://127.0.0.1:8089'
IMAGE=os.environ.get('NATIVE_TEST_IMAGE','freeitsm-native-ldap:test')
NETWORK=os.environ.get('NATIVE_TEST_NETWORK','freeitsm-cloud_default')

def run(args,**kwargs):
    return subprocess.run(args,check=True,capture_output=True,**kwargs)

def client():
    return urllib.request.build_opener(urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()))

def request(c,path,data=None,json_body=False):
    payload=(json.dumps(data) if json_body else urllib.parse.urlencode(data)).encode() if data is not None else None
    return c.open(urllib.request.Request(BASE+path,data=payload,headers={'Content-Type':'application/json' if json_body else 'application/x-www-form-urlencoded'}),timeout=30)

def fixture(code):
    prefix='require "/var/www/html/config.php";if(DB_NAME!=="freeitsm_native_ldap_test")exit(99);require_once "/var/www/html/includes/functions.php";$db=connectToDatabase();'
    return run(['docker','exec',APP,'php','-r',prefix+code]).stdout.decode()

def check(v,message):
    if not v:raise AssertionError(message)
    print('PASS '+message,flush=True)

class InlineScripts(HTMLParser):
    def __init__(self):
        super().__init__();self.scripts=[];self.collect=False;self.parts=[]
    def handle_starttag(self,tag,attrs):
        if tag=='script':
            attrs=dict(attrs);self.collect='src' not in attrs and attrs.get('type','text/javascript') in ('text/javascript','application/javascript','')
            self.parts=[]
    def handle_data(self,data):
        if self.collect:self.parts.append(data)
    def handle_endtag(self,tag):
        if tag=='script':
            if self.collect:self.scripts.append(''.join(self.parts))
            self.collect=False

with tempfile.TemporaryDirectory(prefix='native-ldap-') as tmp:
    tmp=Path(tmp)
    admin=secrets.token_hex(24);service=secrets.token_hex(24);password=secrets.token_hex(24)
    appenv=tmp/'app.env';appenv.write_text('DB_SERVER=db\nDB_NAME=freeitsm_native_ldap_test\nDB_USERNAME='+os.environ.get('NATIVE_TEST_DB_USER','root')+'\nDB_PASSWORD='+os.environ['NATIVE_TEST_DB_PASSWORD']+'\n');appenv.chmod(0o600)
    try:
        # Start OpenLDAP directly so the image's bootstrap cannot race test provisioning.
        serverdir=tmp/'server';serverdir.mkdir(mode=0o700)
        conf='include /etc/ldap/schema/core.schema\ninclude /etc/ldap/schema/cosine.schema\ninclude /etc/ldap/schema/inetorgperson.schema\npidfile /tmp/fixture/slapd.pid\nargsfile /tmp/fixture/slapd.args\nmodulepath /usr/lib/ldap\nmoduleload back_mdb\ndatabase mdb\nmaxsize 1073741824\nsuffix "dc=example,dc=test"\nrootdn "cn=admin,dc=example,dc=test"\nrootpw '+admin+'\ndirectory /tmp/fixture/data\naccess to attrs=userPassword by self write by anonymous auth by * none\naccess to * by dn.exact="cn=reader,dc=example,dc=test" read by self read by * none\n'
        (serverdir/'slapd.conf').write_text(conf)
        ldif='dn: dc=example,dc=test\nobjectClass: top\nobjectClass: dcObject\nobjectClass: organization\no: Fixture\ndc: example\n\n'
        ldif+='dn: cn=reader,dc=example,dc=test\nobjectClass: simpleSecurityObject\nobjectClass: organizationalRole\ncn: reader\nuserPassword: '+service+'\n\n'
        for name in ['wirealice','wirebob','wiredana','wiremallory']:
            ldif+=f'dn: uid={name},dc=example,dc=test\nobjectClass: inetOrgPerson\ncn: {name}\nsn: Example\nuid: {name}\nmail: {name}@example.test\nuserPassword: {password}\n\n'
        ldif+='dn: cn=IT,dc=example,dc=test\nobjectClass: groupOfNames\ncn: IT\nmember: uid=wirealice,dc=example,dc=test\nmember: uid=wiredana,dc=example,dc=test\n\n'
        ldif+='dn: cn=Portal,dc=example,dc=test\nobjectClass: groupOfNames\ncn: Portal\nmember: uid=wirebob,dc=example,dc=test\n\n'
        (serverdir/'entries.ldif').write_text(ldif);(serverdir/'reader-password').write_text(service)
        run(['docker','run','-d','--name',LDAP,'--network',NETWORK,'--entrypoint','sleep','osixia/openldap:1.5.0','infinity'])
        run(['docker','cp',str(serverdir),LDAP+':/tmp/fixture'])
        run(['docker','exec',LDAP,'sh','-c','mkdir -p /tmp/fixture/data; slapadd -f /tmp/fixture/slapd.conf -l /tmp/fixture/entries.ldif'])
        run(['docker','exec','-d',LDAP,'slapd','-f','/tmp/fixture/slapd.conf','-h','ldap://0.0.0.0:389','-d','0'])
        for _ in range(60):
            search=subprocess.run(['docker','exec',LDAP,'ldapsearch','-LLL','-x','-H','ldap://127.0.0.1','-D','cn=reader,dc=example,dc=test','-y','/tmp/fixture/reader-password','-b','dc=example,dc=test','-s','base','(objectClass=*)','dn'],capture_output=True,text=True)
            if search.returncode==0 and 'dn: dc=example,dc=test' in search.stdout:break
            time.sleep(.25)
        else:raise RuntimeError('Direct LDAP fixture did not become ready: '+search.stderr)
        check(True,'fixture service account can read its base DN')
        run(['docker','run','-d','--name',APP,'--network',NETWORK,'-p','127.0.0.1:8089:80','--env-file',str(appenv),IMAGE])
        # Overlay current source for repeatability without broadening workspace permissions.
        archive=tmp/'source.tar'
        files=run(['git','ls-files','-m','-o','--exclude-standard'],cwd=REPO).stdout.decode().splitlines()
        with tarfile.open(archive,'w')as t:
            for f in files:
                p=REPO/f
                if p.is_file() and f!='config.php' and '__pycache__' not in f:t.add(p,arcname=f)
        run(['docker','cp',str(archive),APP+':/tmp/source.tar'])
        run(['docker','exec',APP,'sh','-c','tar -xf /tmp/source.tar -C /var/www/html && cp /var/www/html/docker/config.php /var/www/html/config.php && chown -R www-data:www-data /var/www/html'])
        run(['docker','exec',APP,'sh','-c','echo "log_errors=On\nerror_log=/proc/self/fd/2\nzend.exception_ignore_args=On" > /usr/local/etc/php/conf.d/native-test-errors.ini'])
        run(['docker','restart',APP])
        for _ in range(40):
            try:request(client(),'/login.php').close();break
            except urllib.error.HTTPError:raise
            except Exception:time.sleep(.25)
        c=client()
        body=request(c,'/login.php',{'username':os.environ.get('NATIVE_TEST_LOCAL_USERNAME','admin'),'password':os.environ['NATIVE_TEST_LOCAL_PASSWORD']}).read().decode()
        check('Administrator' in body,'local emergency administrator still signs in')
        fixture("$db->exec(\"UPDATE auth_providers SET enabled=0 WHERE protocol='ldap'\");")
        body=request(c,'/system/sso/').read().decode();match=re.search(r'const NATIVE_LDAP_CSRF = "([a-f0-9]+)"',body);check(bool(match),'Authentication page exposes native LDAP and CSRF')
        parser=InlineScripts();parser.feed(body);scripts=parser.scripts
        run(['node','-e','const vm=require("vm");new vm.Script(JSON.parse(require("fs").readFileSync(0,"utf8")).join(String.fromCharCode(10)));'],input=json.dumps(scripts).encode())
        check(True,'rendered Authentication JavaScript parses')
        csrf=match.group(1)
        def api(path,payload,token=csrf):
            req=urllib.request.Request(BASE+path,data=json.dumps(payload).encode(),headers={'Content-Type':'application/json','X-CSRF-Token':token})
            with c.open(req,timeout=30)as r:return json.load(r)
        p={'protocol':'ldap','ldap_flavour':'openldap','display_name':'Wire LDAP','ldap_host':LDAP,'ldap_port':389,'ldap_encryption':'none','ldap_bind_dn':'cn=reader,dc=example,dc=test','ldap_bind_password':service,'ldap_base_dn':'dc=example,dc=test','ldap_group_base_dn':'dc=example,dc=test','ldap_analyst_group':'IT','ldap_user_group':'Portal','enabled':1,'auto_create_analysts':1,'auto_create_users':1,'default_modules':'tickets,knowledge'}
        result=api('/api/system/save_sso_provider.php',p);check(result.get('success'),'native provider save API works');pid=int(result['id']);p['id']=pid
        # Repeated fixture runs use a new directory namespace but retain test account IDs.
        fixture('$db->prepare("UPDATE analysts SET auth_provider_id=? WHERE username LIKE \'wire%\'")->execute(['+str(pid)+']);$db->prepare("UPDATE users SET auth_provider_id=? WHERE username LIKE \'wire%\'")->execute(['+str(pid)+']);')
        defaults={'ldap_user_filter':'(&(objectClass=inetOrgPerson)(|(uid=%s)(mail=%s)))','ldap_attr_username':'uid','ldap_attr_email':'mail','ldap_attr_name':'cn','ldap_attr_guid':'entryUUID','ldap_group_filter':'(&(objectClass=groupOfNames)(member=%s))'}
        p.update(defaults)
        result=api('/api/system/test_ldap_connection.php',p)
        if not result.get('success'):
            diagnostic=fixture('require_once "/var/www/html/includes/ldap.php";$p=ldapGetProvider($db,'+str(pid)+');echo json_encode(["host"=>$p["ldap_host"],"base"=>$p["ldap_base_dn"],"check"=>ldapTestConnection($p)]);')
            raise AssertionError('LDAP API failed: '+result.get('error','')+'; saved-provider CLI probe: '+diagnostic)
        check(True,'real read-only LDAP service bind and search')
        p['test_username']='wirealice';p['test_password']=password
        result=api('/api/system/test_ldap_connection.php',p);check(result.get('success') and result['found']['role']=='analyst','real user bind and analyst group resolution')
        listed=json.load(request(c,'/api/system/get_sso_providers.php'));check(service not in json.dumps(listed) and 'ldap_bind_password' not in json.dumps(listed),'provider listing never exposes service passwords')
        a=client();resp=request(a,'/login.php',{'username':'wirealice','password':password});body=resp.read().decode();check(resp.url.endswith('index.php') and 'wirealice' in body.lower(),'directory analyst login reaches dashboard')
        try:request(a,'/system/sso/');raise AssertionError('Restricted analyst reached admin')
        except urllib.error.HTTPError as e:check(e.code==403,'restricted directory analyst denied administrative configuration')
        b=client();data=json.load(request(b,'/api/self-service/login.php',{'identifier':'wirebob','password':password},True));check(data.get('success'),'directory requester login by username succeeds')
        data=json.load(request(client(),'/api/self-service/login.php',{'identifier':'wirebob','password':'incorrect'},True));check(not data.get('success'),'wrong directory password denied without local fallback')
        data=json.load(request(client(),'/api/self-service/login.php',{'identifier':'wiremallory','password':password},True));check(not data.get('success'),'correct password without group membership is denied')
        # Existing local TOTP must remain required after directory adoption.
        fixture('require_once "/var/www/html/includes/encryption.php";require_once "/var/www/html/includes/totp.php";$s=$db->prepare("INSERT INTO users(email,username,display_name,totp_enabled,totp_secret,auth_provider_id)VALUES(?,?,?,?,?,?) ON DUPLICATE KEY UPDATE totp_enabled=1,totp_secret=VALUES(totp_secret),auth_provider_id=VALUES(auth_provider_id)");$s->execute(["wiredana@example.test","wiredana","Dana",1,encryptValue(generateTotpSecret()),'+str(pid)+']);')
        data=json.load(request(client(),'/api/self-service/login.php',{'identifier':'wiredana','password':password},True));check(data.get('mfa_required'),'TOTP preserved after successful directory password verification')
        # OIDC and LDAP share storage, not entry points.
        result=json.load(request(c,'/api/auth/resolve_login.php',{'email':'wirebob@example.test','portal':'self-service'},True));check(result.get('mode')=='local','LDAP email router stays on password form')
        result=api('/api/system/delete_sso_provider.php',{'id':pid});check(not result.get('success'),'assigned LDAP provider cannot be accidentally deleted')
        try:api('/api/system/save_sso_provider.php',p,token='invalid');raise AssertionError('CSRF accepted')
        except urllib.error.HTTPError as e:check(e.code==403,'provider mutation rejects invalid CSRF')
        for old in ['/ad-login.php','/ad-permissions.php']:
            check(request(client(),old).url.endswith('login.php'),'retired '+old+' redirects to native login')
        print('Native LDAP HTTP and real directory fixture passed.',flush=True)
    except Exception:
        logs=subprocess.run(['docker','logs','--tail','25',APP],capture_output=True,text=True)
        output=logs.stdout+logs.stderr
        for value in [admin,service,password,os.environ['NATIVE_TEST_DB_PASSWORD'],os.environ['NATIVE_TEST_LOCAL_PASSWORD']]:
            output=output.replace(value,'[redacted]')
        print(output,flush=True)
        raise
    finally:
        for name in [APP,LDAP]:subprocess.run(['docker','rm','-f','-v',name],capture_output=True)
