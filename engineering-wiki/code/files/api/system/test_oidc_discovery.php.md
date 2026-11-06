# api/system/test_oidc_discovery.php

> API endpoint - 84 lines

API: Test an OIDC issuer's discovery document. POST JSON { issuer_url }

Fetches <issuer>/.well-known/openid-configuration and confirms it exposes
the endpoints we need (authorization, token, jwks). This lets an admin
validate a provider's issuer URL before saving — the same discovery the
login flow will rely on, so "one config form works for every IdP".

## The endpoint

| | |
|---|---|
| URL | `api/system/test_oidc_discovery.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [sslApplyCurl()](/code/functions/sslApplyCurl.md)

