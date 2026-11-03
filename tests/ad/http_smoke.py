"""HTTP regression checks for a local container using freeitsm_ad_test only."""
import http.cookiejar
import os
import subprocess
import unittest
import urllib.error
import urllib.parse
import urllib.request
import re

BASE = os.environ.get('AD_HTTP_URL', 'http://127.0.0.1:8088')
CONTAINER = os.environ.get('AD_HTTP_CONTAINER', 'freeitsm-ad-http')
assert urllib.parse.urlparse(BASE).hostname in ('127.0.0.1', 'localhost')


def fixture(php):
    code = ('require "/var/www/html/config.php"; '
            'if (DB_NAME !== "freeitsm_ad_test") { exit(99); } '
            'require_once "/var/www/html/includes/ad/bootstrap.php"; $s=adService(); ' + php)
    subprocess.run(['docker', 'exec', '--user', 'www-data', CONTAINER, 'php', '-r', code], check=True, capture_output=True)


class HttpTests(unittest.TestCase):
    def setUp(self):
        self.jar = http.cookiejar.CookieJar()
        self.client = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(self.jar))

    def request(self, path, data=None):
        payload = urllib.parse.urlencode(data).encode() if data is not None else None
        try:
            with self.client.open(BASE + path, payload, timeout=15) as response:
                return response.status, response.read().decode(), response.url
        except urllib.error.HTTPError as error:
            return error.code, error.read().decode(), error.url

    def local_login(self):
        self.request('/login.php?local=1')
        status, body, url = self.request('/login.php?local=1', {'username': 'admin', 'password': 'freeitsm'})
        self.assertEqual(status, 200)
        self.assertIn('Administrator', body)
        self.assertTrue(url.endswith('index.php'))

    def csrf(self):
        status, body, _ = self.request('/ad-login.php')
        self.assertEqual(status, 200)
        return re.search(r'name="csrf" value="([a-f0-9]+)"', body).group(1)

    def support(self):
        fixture('$s->query("UPDATE ad_profiles SET authorization_status=\'current\', refreshed_at=UTC_TIMESTAMP(), revision=(SELECT revision FROM ad_settings WHERE id=1) WHERE email=\'alice@example.test\'"); '
                'session_id("ad-http-support-fixture"); session_start(); $_SESSION=["ad_profile_id"=>1]; session_write_close();')
        self.jar.set_cookie(http.cookiejar.Cookie(0, 'PHPSESSID', 'ad-http-support-fixture', None, False, '127.0.0.1', False, False, '/', True, False, None, True, None, None, {}))

    def test_local_login_and_administration(self):
        self.local_login()
        status, body, _ = self.request('/system/active-directory/')
        self.assertEqual(status, 200)
        self.assertIn('User Authorization', body)
        self.assertNotIn('test-service-secret', body)
        self.assertNotIn('password_encrypted', body)

    def test_admin_csrf_rejection(self):
        self.local_login()
        status, body, _ = self.request('/system/active-directory/', {'action': 'review_profile', 'email': 'forged@example.test', 'csrf': 'wrong'})
        self.assertEqual(status, 200)
        self.assertIn('csrf_failed', body)
        fixture('if($s->query("SELECT 1 FROM ad_profiles WHERE email=\'forged@example.test\'")->fetchColumn()) exit(1);')

    def test_anonymous_diagnostics_are_denied(self):
        status, _, _ = self.request('/system/active-directory/?profile_id=1')
        self.assertEqual(status, 403)

    def test_corporate_form_and_registration_response(self):
        token = self.csrf()
        status, body, _ = self.request('/ad-login.php', {'action': 'register', 'email': 'not-corporate@other.test', 'csrf': token})
        self.assertEqual(status, 200)
        self.assertIn('If this address is eligible', body)

    def test_invalid_directory_login_is_generic(self):
        token = self.csrf()
        status, body, _ = self.request('/ad-login.php', {'action': 'login', 'email': 'unknown@example.test', 'password': 'incorrect', 'csrf': token})
        self.assertEqual(status, 200)
        self.assertIn('Sign in failed or authorization is unavailable', body)
        self.assertNotIn('LDAP', body)

    def test_guest_cannot_visit_analyst_routes(self):
        fixture('session_id("ad-http-guest-fixture"); session_start(); $_SESSION=["ad_profile_id"=>(int)$s->query("SELECT id FROM ad_profiles WHERE email=\'noauthority@example.test\'")->fetchColumn()]; session_write_close();')
        self.jar.set_cookie(http.cookiejar.Cookie(0, 'PHPSESSID', 'ad-http-guest-fixture', None, False, '127.0.0.1', False, False, '/', True, False, None, True, None, None, {}))
        status, _, _ = self.request('/system/')
        self.assertEqual(status, 403)

    def test_support_cannot_visit_admin_routes(self):
        self.support()
        status, _, _ = self.request('/system/active-directory/')
        self.assertEqual(status, 403)

    def test_support_can_use_requester_portal(self):
        self.support()
        status, body, _ = self.request('/self-service/index.php')
        self.assertEqual(status, 200)
        self.assertIn('Alice', body)

    def test_expired_authorization_denies_access_on_real_requests(self):
        self.support()
        fixture('$s->query("UPDATE ad_profiles SET refreshed_at=UTC_TIMESTAMP()-INTERVAL 901 SECOND WHERE id=1");')
        status, _, _ = self.request('/index.php')
        self.assertEqual(status, 403)
        status, body, _ = self.request('/ad-permissions.php')
        self.assertEqual(status, 200)
        self.assertIn('Authorization unavailable', body)

    def test_support_cannot_mutate_users_via_ticket_admin_endpoints(self):
        self.support()
        status, _, _ = self.request('/api/tickets/save_user.php', {'id': '1', 'email': 'changed@example.test'})
        self.assertEqual(status, 403)

    def test_local_recovery_clears_failed_ad_context(self):
        self.support()
        fixture('$s->query("UPDATE ad_profiles SET authorization_status=\'unavailable\' WHERE id=1");')
        self.local_login()
        status, body, _ = self.request('/system/active-directory/')
        self.assertEqual(status, 200)
        self.assertIn('User Authorization', body)

    def test_ad_logout_clears_identity(self):
        self.support()
        self.request('/self-service/logout.php')
        status, body, _ = self.request('/ad-permissions.php')
        self.assertEqual(status, 200)
        self.assertIn('Corporate sign in', body)
        self.assertNotIn('My Profile — Permissions', body)


if __name__ == '__main__':
    unittest.main(verbosity=2)
