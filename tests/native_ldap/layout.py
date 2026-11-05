"""Browser regression for LDAP settings visibility. Use an isolated test app."""
import os
from urllib.parse import urlparse
from playwright.sync_api import sync_playwright

base=os.environ['LDAP_LAYOUT_URL'].rstrip('/')
assert urlparse(base).hostname in ('127.0.0.1','localhost'), 'Use a local isolated test app'
with sync_playwright() as p:
    browser=p.chromium.launch(executable_path=os.environ.get('CHROMIUM_PATH','/usr/bin/chromium'),headless=True,args=['--no-sandbox'])
    page=browser.new_page(viewport={'width':1366,'height':900})
    page.route('**/*',lambda route: route.continue_() if urlparse(route.request.url).hostname in ('127.0.0.1','localhost') else route.abort())
    errors=[]
    page.on('pageerror',lambda error: errors.append(str(error)))
    page.goto(base+'/login.php?local=1')
    page.locator('input[name="username"]').fill(os.environ['LDAP_LAYOUT_USERNAME'])
    page.locator('input[name="password"]').fill(os.environ['LDAP_LAYOUT_PASSWORD'])
    page.locator('#localLoginForm button[type="submit"]').click()
    page.wait_for_url('**/index.php')
    page.goto(base+'/system/sso/')
    assert page.locator('.sso-container #ldapPanel').count()==1, 'LDAP panel must be inside the visible scrolling container'
    page.get_by_role('button',name='+ Add LDAP',exact=True).click()
    assert page.locator('#ldapPanel').get_attribute('open') is not None
    field=page.locator('#ldap_ldap_host')
    field.scroll_into_view_if_needed()
    box=field.bounding_box()
    assert box and box['y']>=0 and box['y']+box['height']<=900, 'LDAP hostname field is clipped outside the viewport'
    assert field.is_editable()
    print('PASS Add LDAP opens an editable form inside the visible scroll region')
    page.get_by_role('button',name='+ Add OIDC',exact=True).click()
    assert page.locator('#providerModal').is_visible()
    assert page.locator('#fIssuerUrl').is_visible()
    print('PASS Add OIDC still opens the OIDC provider dialog')
    assert not errors, 'Browser script errors: '+str(errors)
    print('PASS Authentication page has no browser JavaScript errors')
    browser.close()
