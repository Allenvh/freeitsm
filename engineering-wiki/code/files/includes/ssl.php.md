# includes/ssl.php

> PHP - 110 lines

Central SSL/TLS policy for outbound HTTPS.

Every outbound cURL handle in the app routes its certificate-verification
setup through `sslApplyCurl()`. That gives us a single global switch
(SSL_VERIFY_PEER, in config.php) instead of the per-module "Verify SSL"
tick-boxes we used to scatter across settings pages — which were confusing
(some ANDed with the global, some independent) and, worse, let the UI imply
a security state the server couldn't actually deliver.

Why CURLOPT_CAINFO rather than php.ini or an env var:
  On a stock Windows/WAMP install, Apache's php.ini has no `curl.cainfo`, so
  verification fails with "unable to get local issuer certificate". You can't
  fix that from PHP at runtime — `curl.cainfo` is PHP_INI_SYSTEM (ini_set has
  no effect) and this libcurl build ignores the CURL_CA_BUNDLE env var. The
  only mechanism that works without hand-editing php.ini is setting the bundle
  per-handle with CURLOPT_CAINFO, which is what we ship a cacert.pem for.

WHY BOTH DEFINITIONS ARE GUARDED BY `function_exists()`, same as includes/db.php:
every caller loads config.php first, and config.php requires this file. An
operator whose own config.php carries a hand-pasted copy of either function
would otherwise trade "undefined function" for "cannot redeclare" - the same
outage in the other direction. Theirs wins; this fills the hole for everyone
else. See GH #129.

AND WHY EVERY DIRECTLY-REQUESTABLE ENTRY POINT MUST REQUIRE THIS FILE ITSELF:
reaching `sslApplyCurl()` only through config.php means the operator's file is
load-bearing, which is exactly what #129 forbids. Most callers arrive via
includes/functions.php, which requires this file; the OAuth callbacks
deliberately do not load functions.php, so they require it directly.
tests/config-not-load-bearing.php enforces that structurally.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sslResolveCaBundle()](/code/functions/sslResolveCaBundle.md) | 50 | The CA bundle file to verify TLS with: PHP's configured one, else the bundled cacert.pem on Windows, else '' for the system store. |
| [sslApplyCurl()](/code/functions/sslApplyCurl.md) | 89 | Applies the install's TLS verification setting, and the CA bundle, to a cURL handle. |

## How it hangs together

**Included by:** [api/integrations/test_connection.php](/code/files/api/integrations/test_connection.php.md), [config.php](/code/files/config.php.md), [includes/calendar_sync/MicrosoftCalendarProvider.php](/code/files/includes/calendar_sync/MicrosoftCalendarProvider.php.md), [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), [includes/domains/tls.php](/code/files/includes/domains/tls.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/hypervisor_http.php](/code/files/includes/hypervisor_http.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), [includes/search/tika.php](/code/files/includes/search/tika.php.md)

