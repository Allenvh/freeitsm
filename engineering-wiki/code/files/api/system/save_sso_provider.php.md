# api/system/save_sso_provider.php

> API endpoint - 563 lines

API: Create or update an authentication provider.

Two shapes, selected by `protocol`:
  'oidc' — { id?, display_name, issuer_url, client_id, client_secret, scopes, ... }
  'ldap' — { id?, display_name, ldap_host, ldap_port, ldap_encryption,
             ldap_bind_dn, ldap_bind_password, ldap_base_dn, ldap_user_filter,
             ldap_attr_*, ... }
Shared: enabled, auto_create_users, require_verified_email, default_modules,
        sort_order, tenant_id, auto_create_analysts (discussion #155).
OIDC only in practice: analyst_fallback_mode, profile_sync_mode (#155).
OIDC only: portal_email_domains, portal_show_button (GH #147).

Secrets (client_secret / ldap_bind_password) are encrypted at rest via
`encryptValue()`. On update, a blank or masked ("****") secret means "leave the
stored one untouched" so the admin needn't re-enter it every save.

## The endpoint

| | |
|---|---|
| URL | `api/system/save_sso_provider.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [bail()](/code/functions/bail.md) | 44 | Answers {success: false, error} as JSON and stops. |
| [ssoDnLines()](/code/functions/ssoDnLines.md) | 69 | Normalise a list of DNs (an array, or newline-separated text) for storage. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 392 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 439 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 495 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 531 |  | **update** [auth_providers](/code/tables/auth_providers.md) (`last_modified_datetime`) |  |
| 550 |  | **insert** [auth_providers](/code/tables/auth_providers.md) (`$names`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [encryptValue()](/code/functions/encryptValue.md), [isFreemailDomain()](/code/functions/isFreemailDomain.md), [isMaskedNoChangeValue()](/code/functions/isMaskedNoChangeValue.md), [ssoClaimsReady()](/code/functions/ssoClaimsReady.md), [ssoJitColumnsReady()](/code/functions/ssoJitColumnsReady.md), [ssoJitCompanyReady()](/code/functions/ssoJitCompanyReady.md), [ssoParseEmailDomains()](/code/functions/ssoParseEmailDomains.md), [ssoPortalRoutingColumnsReady()](/code/functions/ssoPortalRoutingColumnsReady.md), [ssoSaveGroupLabels()](/code/functions/ssoSaveGroupLabels.md), [ssoSaveTeamMappings()](/code/functions/ssoSaveTeamMappings.md)

