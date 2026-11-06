"""Disposable full-upgrade integration fixture; never point at a normal installation.
Requires freeitsm-upgrade-test on 127.0.0.1:8092, its freeitsm_upgrade_test
schema, and the freeitsm-cloud_default Docker network. The PHP bootstrap refuses
other database names. Credentials are generated per run and never printed.
"""
import subprocess,tempfile,pathlib,secrets,json,time,urllib.request,urllib.parse,http.cookiejar
APP='freeitsm-upgrade-test';LDAP='freeitsm-upgrade-ldap';BASE='http://127.0.0.1:8092';values=[]
def run(a):
 p=subprocess.run(a,capture_output=True,text=True)
 if p.returncode:
  msg=p.stderr+p.stdout
  for v in values:msg=msg.replace(v,'[redacted]')
  raise RuntimeError(msg)
 return p.stdout
def client():return urllib.request.build_opener(urllib.request.ProxyHandler({}),urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()))
def request(c,path,data=None,json_body=False):
 body=(json.dumps(data) if json_body else urllib.parse.urlencode(data)).encode() if data is not None else None
 return c.open(urllib.request.Request(BASE+path,data=body,headers={'Content-Type':'application/json' if json_body else 'application/x-www-form-urlencoded'}),timeout=20)
if subprocess.run(['docker','inspect',LDAP],capture_output=True).returncode==0:raise RuntimeError('Fixture container already exists; refusing to replace it')
created=False
with tempfile.TemporaryDirectory(prefix='upgrade-ldap-') as tmp:
 tmp=pathlib.Path(tmp);fixture=tmp/'fixture';fixture.mkdir();suffix=secrets.token_hex(4)
 admin,reader,password=[secrets.token_hex(24) for _ in range(3)];values=[admin,reader,password]
 alice='upgradealice'+suffix;bob='upgradebob'+suffix
 conf='include /etc/ldap/schema/core.schema\ninclude /etc/ldap/schema/cosine.schema\ninclude /etc/ldap/schema/inetorgperson.schema\npidfile /tmp/fixture/slapd.pid\nargsfile /tmp/fixture/slapd.args\nmodulepath /usr/lib/ldap\nmoduleload back_mdb\ndatabase mdb\nmaxsize 1073741824\nsuffix "dc=example,dc=test"\nrootdn "cn=admin,dc=example,dc=test"\nrootpw '+admin+'\ndirectory /tmp/fixture/data\naccess to attrs=userPassword by self write by anonymous auth by * none\naccess to * by dn.exact="cn=reader,dc=example,dc=test" read by self read by * none\n'
 (fixture/'slapd.conf').write_text(conf)
 ldif='dn: dc=example,dc=test\nobjectClass: dcObject\nobjectClass: organization\no: Upgrade fixture\ndc: example\n\ndn: cn=reader,dc=example,dc=test\nobjectClass: simpleSecurityObject\nobjectClass: organizationalRole\ncn: reader\nuserPassword: '+reader+'\n\n'
 for name in [alice,bob]:ldif+='dn: uid='+name+',dc=example,dc=test\nobjectClass: inetOrgPerson\ncn: '+name+'\nsn: Example\nuid: '+name+'\nmail: '+name+'@example.test\nuserPassword: '+password+'\n\n'
 for group,name in [('IT',alice),('Portal',bob)]:ldif+='dn: cn='+group+',dc=example,dc=test\nobjectClass: groupOfNames\ncn: '+group+'\nmember: uid='+name+',dc=example,dc=test\n\n'
 (fixture/'entries.ldif').write_text(ldif);(fixture/'password').write_text(reader)
 data={'display_name':'Upgrade LDAP '+suffix,'protocol':'ldap','issuer_url':'','client_id':'','enabled':1,'auto_create_users':1,'auto_create_analysts':1,'default_modules':'tickets,knowledge','ldap_host':LDAP,'ldap_port':389,'ldap_encryption':'none','ldap_bind_dn':'cn=reader,dc=example,dc=test','ldap_bind_password':reader,'ldap_base_dn':'dc=example,dc=test','ldap_user_filter':'(&(objectClass=inetOrgPerson)(uid=%s))','ldap_attr_username':'uid','ldap_attr_email':'mail','ldap_attr_name':'cn','ldap_attr_guid':'entryUUID','ldap_group_base_dn':'dc=example,dc=test','ldap_group_filter':'(&(objectClass=groupOfNames)(member=%s))','ldap_analyst_group':'IT','ldap_user_group':'Portal'}
 payload=tmp/'provider.json';payload.write_text(json.dumps({'provider':data,'alice':alice,'bob':bob,'password':password}));payload.chmod(0o600)
 try:
  run(['docker','run','-d','--name',LDAP,'--network','freeitsm-cloud_default','--entrypoint','sleep','osixia/openldap:1.5.0','infinity']);created=True;run(['docker','cp',str(fixture),LDAP+':/tmp/fixture'])
  run(['docker','exec',LDAP,'sh','-c','mkdir /tmp/fixture/data; slapadd -f /tmp/fixture/slapd.conf -l /tmp/fixture/entries.ldif']);run(['docker','exec','-d',LDAP,'slapd','-f','/tmp/fixture/slapd.conf','-h','ldap://0.0.0.0:389','-d','0'])
  for _ in range(40):
   p=subprocess.run(['docker','exec',LDAP,'ldapsearch','-LLL','-x','-H','ldap://127.0.0.1','-D','cn=reader,dc=example,dc=test','-y','/tmp/fixture/password','-b','dc=example,dc=test','-s','base','(objectClass=*)','dn'],capture_output=True)
   if p.returncode==0:break
   time.sleep(.2)
  else:raise RuntimeError('Directory did not become ready')
  run(['docker','cp',str(payload),APP+':/tmp/ldap-live.json'])
  code='''require 'config.php';require 'includes/functions.php';require 'includes/encryption.php';require 'includes/ldap.php';if(DB_NAME!=='freeitsm_upgrade_test')exit(99);$c=json_decode(file_get_contents('/tmp/ldap-live.json'),true);$p=$c['provider'];$p['ldap_bind_password']=encryptValue($p['ldap_bind_password']);$db=connectToDatabase();$cols=array_keys($p);$db->prepare('INSERT INTO auth_providers(`'.implode('`,`',$cols).'`) VALUES('.implode(',',array_fill(0,count($cols),'?')).')')->execute(array_values($p));$id=(int)$db->lastInsertId();$p=ldapGetProvider($db,$id);$r=ldapAuthenticate($p,$c['alice'],$c['password']);if(!$r['ok']||ldapAccessRole($p,$r['user'])!=='analyst')throw new RuntimeException('LDAP bind/group failure');foreach(['',"\\0",'wrong'] as $bad){if(ldapAuthenticate($p,$c['alice'],$bad)['ok'])throw new RuntimeException('Invalid password accepted');}echo json_encode(['provider_id'=>$id]);'''
  pid=json.loads(run(['docker','exec',APP,'php','-r',code]))['provider_id']
  print('PASS real LDAP service bind, user bind and analyst group resolution',flush=True)
  print('PASS LDAP rejects empty, NUL and incorrect passwords',flush=True)
  c=client();res=request(c,'/auth/login.php',{'username':alice,'password':password});body=res.read().decode();assert res.url.endswith('/index.php'),res.url
  print('PASS native LDAP analyst login through upgraded HTTP login page',flush=True)
  res=request(c,'/system/sso/');assert '/system/sso/' not in res.url
  try:request(c,'/api/system/get_sso_providers.php');raise AssertionError('Non-admin reached SSO API')
  except urllib.error.HTTPError as e:assert e.code==403
  print('PASS native LDAP analyst cannot reach System authentication settings',flush=True)
  c=client();res=json.load(request(c,'/api/self-service/login.php',{'identifier':bob,'password':password},True));assert res.get('success'),str(res)
  print('PASS native LDAP requester login through self-service HTTP API',flush=True)
  bad=json.load(request(client(),'/api/self-service/login.php',{'identifier':bob,'password':'incorrect'},True));assert not bad.get('success')
  print('PASS requester wrong password denied',flush=True)
 finally:
  if created:subprocess.run(['docker','rm','-f',LDAP],capture_output=True)
