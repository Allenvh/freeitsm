"""Read-only HTTP regressions against a local development instance with AD disabled.
Requires the documented development admin/freeitsm seed account. Never use production.
"""
import http.cookiejar,json,os,re,urllib.error,urllib.parse,urllib.request
base=os.environ.get('FREEITSM_TEST_BASE_URL','http://127.0.0.1:8080/').rstrip('/')+'/'
assert urllib.parse.urlsplit(base).hostname in ('127.0.0.1','localhost','::1'), 'Local development instances only'
opener=urllib.request.build_opener(urllib.request.ProxyHandler({}),urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()))
def request(path,data=None,headers=None):
    try:
        r=opener.open(urllib.request.Request(base+path,data=data,headers=headers or {}),timeout=30)
    except urllib.error.HTTPError as e:r=e
    return r.status,r.read().decode()
passed=0
def check(condition,name):
    global passed
    assert condition,name
    passed+=1
status,body=request('ad-sign-in.php')
check(status==200 and 'Corporate AD sign-in is not enabled.' in body,'disabled native sign-in page')
status,body=request('api/ad/admin.php')
check(status==400 and 'providers' not in json.loads(body),'anonymous admin access denied')
status,body=request('api/ad/login.php',b'{"email":"nobody@company.example","password":"bad"}',{'Content-Type':'application/json'})
check(status==401 and not json.loads(body)['success'],'AD login CSRF and disabled-mode rejection')
status,body=request('login.php',urllib.parse.urlencode({'username':'admin','password':'freeitsm'}).encode())
check(status==200 and 'ITSM Login' not in body,'legacy local login preserved')
status,body=request('system/active-directory/')
check(status==200 and 'Deployment settings' in body,'admin configuration UI renders')
csrf_match=re.search(r'const csrf="([a-f0-9]+)"',body)
check(csrf_match is not None,'admin CSRF token generated')
status,body=request('api/ad/admin.php')
payload=json.loads(body)
check(status==200 and payload['success'],'admin read API')
check(not int(payload['config']['enabled']) and not int(payload['config']['multi_domain']) and payload['config']['authority_provider_id'] is None,'backward-compatible optional defaults')
for p in payload['providers']:
    check('bind_password' not in p and 'client_secret' not in p,'provider secrets excluded')
status,body=request('api/ad/admin.php',b'{"action":"config","enabled":1}',{'Content-Type':'application/json','X-CSRF-Token':'invalid'})
check(status==400 and not json.loads(body)['success'],'admin mutation requires CSRF')
status,body=request('api/ad/admin.php')
check(not int(json.loads(body)['config']['enabled']),'rejected mutation leaves settings unchanged')
status,body=request('self-service/login.php')
check(status==200 and 'Corporate Active Directory sign-in' not in body,'disabled AD does not change portal sign-in choices')
print(f'PASS: {passed} HTTP checks (local development; AD disabled).')
