<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * OIDC discovery endpoint validation (GH issue #117).
 *
 * The assertion that matters is not "a good discovery document is accepted".
 * It is that a BAD one is refused HERE, where we still know whose fault it is.
 *
 * A provider that publishes `authorization_endpoint` as a bare path
 * ("/oidc/authorize" instead of "https://idp.example.com/oidc/authorize") used
 * to be passed straight through. oidcBuildAuthUrl() then produced a relative
 * `Location:` header, and the BROWSER resolved it against the current origin —
 * this application. The user landed on OUR host, got a 404, and every visible
 * symptom pointed at FreeITSM. That is exactly how #117 was reported to us.
 *
 * So the tests below are mostly negative cases, and the positive controls are
 * there to prove the guard is not simply refusing everything.
 *
 * ⚠️ Touches no database and makes no network calls — oidcIsAbsoluteHttpUrl()
 * is a pure function, which is precisely why it is the thing worth testing.
 *
 * Run:  php tests/oidc-discovery.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/oidc.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

echo "\nOIDC discovery endpoint validation (#117)\n";
echo str_repeat('=', 64) . "\n";

// ---- 1. The addresses real providers publish must all be accepted ---------
echo "\nAccepted — absolute URLs, including the awkward shapes real IdPs use:\n";
$good = [
    'https://www.zodiacrp.dk/oidc/authorize',
    'https://login.microsoftonline.com/00000000-0000-0000-0000-000000000000/oauth2/v2.0/authorize',
    'https://accounts.google.com/o/oauth2/v2/auth',
    'https://keycloak.example.com:8443/realms/itsm/protocol/openid-connect/auth',
    'http://localhost:8080/realms/dev/protocol/openid-connect/auth',
    'https://idp.example.com/authorize?acr_values=urn%3Amace%3Aloa2',
];
foreach ($good as $u) {
    ok("accepts {$u}", oidcIsAbsoluteHttpUrl($u), 'a legitimate endpoint was refused');
}

// ---- 2. The shape that caused #117 ---------------------------------------
echo "\nRefused — relative addresses (the #117 shape):\n";
$relative = [
    '/oidc/authorize',            // the exact form in the report
    'oidc/authorize',
    '//idp.example.com/authorize', // protocol-relative: no scheme of its own
    '/',
    '',
    '   ',
];
foreach ($relative as $u) {
    ok("refuses " . ($u === '' ? '(empty string)' : (trim($u) === '' ? '(whitespace)' : $u)),
        !oidcIsAbsoluteHttpUrl($u),
        'a relative address would be resolved against THIS host by the browser');
}

// ---- 3. Schemes we must never hand to a browser as a redirect -------------
// filter_var(FILTER_VALIDATE_URL) accepts several of these, which is why the
// helper does not use it.
echo "\nRefused — non-http schemes (never redirect a browser to these):\n";
$badScheme = [
    'javascript:alert(1)',
    'data:text/html;base64,PHNjcmlwdD4=',
    'mailto:admin@example.com',
    'ftp://files.example.com/authorize',
    'file:///etc/passwd',
    'https://',                    // scheme but no host
];
foreach ($badScheme as $u) {
    ok("refuses {$u}", !oidcIsAbsoluteHttpUrl($u), 'unsafe or hostless scheme accepted');
}

// ---- 4. The guard is wired into oidcDiscover(), not just defined ---------
// The helper being correct is worthless if the discovery path never calls it.
// oidcDiscover() makes a network request before it validates, so drive the
// check the same way it runs: assert the source calls the helper for all three
// required endpoints, and that the message names the provider as the culprit.
echo "\nWired in — oidcDiscover() actually applies it:\n";
$src = file_get_contents(__DIR__ . '/../includes/oidc.php');
$discoverBody = '';
if (preg_match('/function oidcDiscover\(.*?\n\}/s', $src, $m)) $discoverBody = $m[0];

ok('oidcDiscover() was located in the source', $discoverBody !== '');
ok('oidcDiscover() calls oidcIsAbsoluteHttpUrl()',
    str_contains($discoverBody, 'oidcIsAbsoluteHttpUrl('),
    'the helper exists but discovery never consults it');
foreach (['authorization_endpoint', 'token_endpoint', 'jwks_uri'] as $key) {
    ok("validates {$key}", str_contains($discoverBody, $key), "{$key} is not covered by the guard");
}
ok('the failure message blames the identity provider, not FreeITSM',
    str_contains($discoverBody, 'not in FreeITSM'),
    'an admin reading this error must be pointed at the IdP');

// ---- 5. And the browser-facing builder still uses the value verbatim -----
// #117 alleged that FreeITSM rewrites the provider's host. It does not, and a
// regression here would make that allegation true.
echo "\nNo host rewriting — the published endpoint is used verbatim:\n";
$buildBody = '';
if (preg_match('/function oidcBuildAuthUrl\(.*?\n\}/s', $src, $m)) $buildBody = $m[0];
ok('oidcBuildAuthUrl() was located in the source', $buildBody !== '');
ok("oidcBuildAuthUrl() returns the provider's own authorization_endpoint",
    str_contains($buildBody, "\$disco['authorization_endpoint']"),
    'the authorization URL is no longer taken straight from discovery');
ok('oidcBuildAuthUrl() never substitutes a local host',
    !str_contains($buildBody, 'HTTP_HOST') && !str_contains($buildBody, 'BASE_URL'),
    'the local host leaked into the address the browser is redirected to');

echo "\n" . str_repeat('=', 64) . "\n";
echo "  {$pass} passed, {$fail} failed\n\n";
exit($fail > 0 ? 1 : 0);
