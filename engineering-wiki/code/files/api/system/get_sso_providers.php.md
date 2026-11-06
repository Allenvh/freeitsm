# api/system/get_sso_providers.php

> API endpoint - 132 lines

API: List configured authentication providers (OIDC and LDAP). GET - returns every provider. Secrets are NEVER returned in plaintext; only `has_secret` / `has_bind_password` flags tell the UI whether one is stored.

Admin-gated: this returns sign-in infrastructure detail (issuer URLs,
directory hostnames, service-account bind DNs) that a normal analyst has no
reason to see. Only the admin-only System > Authentication page calls it.

## The endpoint

| | |
|---|---|
| URL | `api/system/get_sso_providers.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [auth_providers](/code/tables/auth_providers.md), read [tenants](/code/tables/tenants.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), [includes/sso_claims.php](/code/files/includes/sso_claims.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [ssoClaimsReady()](/code/functions/ssoClaimsReady.md), [ssoJitColumnsReady()](/code/functions/ssoJitColumnsReady.md), [ssoJitCompanyReady()](/code/functions/ssoJitCompanyReady.md), [ssoPortalRoutingColumnsReady()](/code/functions/ssoPortalRoutingColumnsReady.md), [ssoSeenGroups()](/code/functions/ssoSeenGroups.md), [ssoTeamMappings()](/code/functions/ssoTeamMappings.md)

