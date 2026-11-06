# includes/ldap.php

> PHP - 909 lines

LDAP / Active Directory helper.

Authenticates a user against a directory by BIND: we bind to the directory
as that user with the password they typed, and a successful bind IS the
proof the password is right. We never read or compare password hashes.

Because a user types "alice", not their full DN, this is a two-step dance:
  1. bind as a read-only service account so we are allowed to search,
  2. search for the user to discover their real DN,
  3. re-bind as that DN with the typed password.

This is NOT single sign-on: the user still types their password into our own
login form. It shares the `auth_providers` table with OIDC (protocol='ldap')
so there is one place to configure "how people sign in".

Supports both Active Directory (sAMAccountName / objectGUID / memberOf) and
OpenLDAP (uid / entryUUID) — every attribute name is configurable precisely
so the code does not silently assume one flavour.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ldapFlavourDefaults()](/code/functions/ldapFlavourDefaults.md) | 35 | Sensible per-flavour defaults, offered by the admin UI as presets. 'ad' = Active Directory / Samba AD DC, 'openldap' = OpenLDAP / 389 / FreeIPA. |
| [ldapExtensionAvailable()](/code/functions/ldapExtensionAvailable.md) | 65 | True when the PHP ldap extension is present. Checked before every use. |
| [ldapGetProvider()](/code/functions/ldapGetProvider.md) | 75 | Load an auth_providers row by id with the bind password decrypted. Returns null if not found or not an LDAP provider. |
| [ldapBuildUri()](/code/functions/ldapBuildUri.md) | 89 | Build the connection URI, e.g. ldaps://dc1.example.com:636 |
| [ldapStringifyId()](/code/functions/ldapStringifyId.md) | 105 | Turn a raw attribute value into something storable as text. |
| [ldapOpen()](/code/functions/ldapOpen.md) | 120 | Open a connection and apply the options that matter. Throws on failure. Does NOT bind. |
| [ldapBindService()](/code/functions/ldapBindService.md) | 152 | Bind as the configured read-only service account. Throws with a useful message on failure. |
| [ldapFindUser()](/code/functions/ldapFindUser.md) | 179 | Find exactly one directory entry for `$login`. Returns the raw entry array (from ldap_get_entries) or null when not found. Throws when the search itself fails or matches more than one user. |
| [ldapAttr()](/code/functions/ldapAttr.md) | 221 | Read a single attribute out of an `ldap_get_entries()` row (keys are lowercased). |
| [ldapUserGroups()](/code/functions/ldapUserGroups.md) | 246 | Every group the user belongs to, as ['dn' => ..., 'cn' => ...]. |
| [ldapInGroup()](/code/functions/ldapInGroup.md) | 280 | Is the user in `$wanted`? `$wanted` may be a plain group name ("NW-IT-Support") or a full DN — admins reach for either, so accept both. Matching is case-insensitive because directories treat these names that way. |
| [ldapAuthenticate()](/code/functions/ldapAuthenticate.md) | 307 | Authenticate `$login`/`$password` against the directory. |
| [ldapAccessRole()](/code/functions/ldapAccessRole.md) | 419 | Decide what an authenticated directory user is allowed to be. |
| [ldapPortalProviders()](/code/functions/ldapPortalProviders.md) | 466 | The LDAP providers a PORTAL sign-in attempt may be tried against. |
| [ldapAnalystProviders()](/code/functions/ldapAnalystProviders.md) | 520 | Every enabled, analyst-facing LDAP provider, in display order. tenant_id IS NULL = global/analyst-facing, matching how the OIDC analyst login lists providers. |
| [ldapCreateAnalyst()](/code/functions/ldapCreateAnalyst.md) | 555 | Create an analyst from directory attributes, assigned to this provider. |
| [ldapResolveAnalyst()](/code/functions/ldapResolveAnalyst.md) | 615 | Map an authenticated directory user onto an analyst id, creating one if the provider allows it. Mirrors the OIDC resolution order: 1) existing link by (provider, subject), 2) match an existing analyst by email — STRICTLY isolated: the analyst must already be assigned to this provider, 3) just-in-time create, if auto_create_analysts is on (auto_create_users is the PORTAL switch - see `ldapResolveUser()`). |
| [ldapResolveUser()](/code/functions/ldapResolveUser.md) | 743 | Map a directory user to a SELF-SERVICE PORTAL account (`users`), creating one if needed. The portal twin of `ldapResolveAnalyst()`. |
| [ldapTestConnection()](/code/functions/ldapTestConnection.md) | 880 | Admin "Test" helper: prove the service account can connect, bind and search. Optionally also verify one real user's credentials. Returns ['ok' => bool, 'error' => ?string, 'found' => ?array]. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 76 | `ldapGetProvider` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 474 | `ldapPortalProviders` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 488 | `ldapPortalProviders` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 496 | `ldapPortalProviders` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 523 | `ldapAnalystProviders` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 560 | `ldapCreateAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 585 | `ldapCreateAnalyst` | **insert** [analysts](/code/tables/analysts.md) (`username`, `password_hash`, `full_name`, `email`, `is_active`, `created_datetime`, `auth_provider_id`, `can_access_all_modules`) |  |
| 592 | `ldapCreateAnalyst` | **insert** [analyst_modules](/code/tables/analyst_modules.md) (`analyst_id`, `module_key`) |  |
| 621 | `ldapResolveAnalyst` | read [analyst_sso_identities](/code/tables/analyst_sso_identities.md) |  |
| 630 | `ldapResolveAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 648 | `ldapResolveAnalyst` | **update** [analyst_sso_identities](/code/tables/analyst_sso_identities.md) (`last_login_datetime`, `email`) |  |
| 651 | `ldapResolveAnalyst` | **update** [analysts](/code/tables/analysts.md) (`email`) |  |
| 659 | `ldapResolveAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 700 | `ldapResolveAnalyst` | **insert** [analyst_sso_identities](/code/tables/analyst_sso_identities.md) (`analyst_id`, `provider_id`, `subject`, `email`, `linked_datetime`, `last_login_datetime`) |  |
| 754 | `ldapResolveUser` | read [user_sso_identities](/code/tables/user_sso_identities.md) |  |
| 762 | `ldapResolveUser` | read [users](/code/tables/users.md) |  |
| 778 | `ldapResolveUser` | **update** [user_sso_identities](/code/tables/user_sso_identities.md) (`last_login_datetime`, `email`) |  |
| 783 | `ldapResolveUser` | **update** [users](/code/tables/users.md) (`email`) |  |
| 797 | `ldapResolveUser` | read [users](/code/tables/users.md) |  |
| 808 | `ldapResolveUser` | **update** [users](/code/tables/users.md) (`auth_provider_id`) |  |
| 816 | `ldapResolveUser` | **update** [users](/code/tables/users.md) (`username`) |  |
| 848 | `ldapResolveUser` | **insert** [users](/code/tables/users.md) (`email`, `username`, `display_name`, `auth_provider_id`, `tenant_id`, `created_at`) |  |
| 861 | `ldapResolveUser` | **insert** [user_sso_identities](/code/tables/user_sso_identities.md) (`user_id`, `provider_id`, `subject`, `email`, `linked_datetime`, `last_login_datetime`) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [api/self-service/login.php](/code/files/api/self-service/login.php.md), [api/system/test_ldap_connection.php](/code/files/api/system/test_ldap_connection.php.md), [includes/directory_sync.php](/code/files/includes/directory_sync.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [resolveTenantForNewUser()](/code/functions/resolveTenantForNewUser.md), [resolveTenantIdForAddress()](/code/functions/resolveTenantIdForAddress.md), [ssoApplyJitCompanies()](/code/functions/ssoApplyJitCompanies.md), [ssoClearDanglingLink()](/code/functions/ssoClearDanglingLink.md)

