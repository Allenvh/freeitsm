# includes/sso_identity.php

> PHP - 241 lines

Sign-in identity links — the one home for the (provider, subject) → account mapping that both sign-in paths share.

`analyst_sso_identities` and `user_sso_identities` record which directory or
identity-provider identity belongs to which FreeITSM account. OIDC writes and
reads them (api/auth/oidc_callback.php) and so does LDAP/AD (includes/ldap.php),
and both resolve a person the same way: existing link → email match →
just-in-time create. The link lookup runs FIRST in all four branches, which is
why the dangling-link case below had to be fixed in one place rather than four.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ssoClearDanglingLink()](/code/functions/ssoClearDanglingLink.md) | 44 | Drop a sign-in link whose account no longer exists. |
| [ssoJitColumnsReady()](/code/functions/ssoJitColumnsReady.md) | 63 | Have the discussion #155 provider columns (auto_create_analysts, analyst_fallback_mode, profile_sync_mode) been added yet? |
| [ssoProfileSyncLocks()](/code/functions/ssoProfileSyncLocks.md) | 89 | Is this provider rewriting profile details on every sign-in? (discussion #155) |
| [ssoAnalystProfileLocked()](/code/functions/ssoAnalystProfileLocked.md) | 97 | Does the given analyst's sign-in provider lock their My details fields? Safe before Database Verification (answers false). |
| [ssoPortalRoutingColumnsReady()](/code/functions/ssoPortalRoutingColumnsReady.md) | 116 | Have the GH #147 portal routing columns (portal_email_domains, portal_show_button) been added yet? Same reason as `ssoJitColumnsReady()`: the portal login and the resolver must keep working on an upgraded install that has not run Database Verification. The two arrive together. |
| [ssoParseEmailDomains()](/code/functions/ssoParseEmailDomains.md) | 142 | Split what an admin typed into the "Email domains" box into clean domains. |
| [ssoPortalProviderForEmail()](/code/functions/ssoPortalProviderForEmail.md) | 171 | The OIDC provider whose "Email domains" list holds this address's domain, or null. Used only by the portal's email-first router (GH #147). |
| [ssoJitCompanyReady()](/code/functions/ssoJitCompanyReady.md) | 193 | True once Database Verification has added auth_providers.jit_company_access (3.5.0). |
| [ssoApplyJitCompanies()](/code/functions/ssoApplyJitCompanies.md) | 219 | Which companies an analyst that SSO or LDAP has just CREATED can see (3.5.0). Called by `oidcCreateAnalyst()` and `ldapCreateAnalyst()` right after the insert. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 67 | `ssoJitColumnsReady` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 100 | `ssoAnalystProfileLocked` | read [analysts](/code/tables/analysts.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 120 | `ssoPortalRoutingColumnsReady` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 179 | `ssoPortalProviderForEmail` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 196 | `ssoJitCompanyReady` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 223 | `ssoApplyJitCompanies` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 228 | `ssoApplyJitCompanies` | **update** [analysts](/code/tables/analysts.md) (`can_access_all_tenants`) |  |
| 229 | `ssoApplyJitCompanies` | **delete** [analyst_tenant_access](/code/tables/analyst_tenant_access.md) |  |
| 232 | `ssoApplyJitCompanies` | **insert** [analyst_tenant_access](/code/tables/analyst_tenant_access.md) (`analyst_id`, `tenant_id`) |  |
| 237 | `ssoApplyJitCompanies` | **update** [analysts](/code/tables/analysts.md) (`can_access_all_tenants`) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md), [api/auth/resolve_login.php](/code/files/api/auth/resolve_login.php.md), [api/myaccount/get_signatures.php](/code/files/api/myaccount/get_signatures.php.md), [api/myaccount/save_profile.php](/code/files/api/myaccount/save_profile.php.md), [api/system/get_sso_providers.php](/code/files/api/system/get_sso_providers.php.md), [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md), [includes/ldap.php](/code/files/includes/ldap.php.md), [includes/oidc.php](/code/files/includes/oidc.php.md), [includes/users.php](/code/files/includes/users.php.md), [self-service/login.php](/code/files/self-service/login.php.md)

**Calls:** [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

