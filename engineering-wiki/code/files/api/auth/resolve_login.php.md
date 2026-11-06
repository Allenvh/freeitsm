# api/auth/resolve_login.php

> API endpoint - 134 lines

API: Resolve an email to a login method (for the email-first login router). POST JSON { email, portal? }

portal = 'analyst' (default) resolves against the analysts table;
portal = 'self-service' resolves against the self-service users table.

Returns:
  { mode: 'sso', provider_id, provider_name }  — route straight to one IdP
  { mode: 'choose', providers: [{id,name}] }   — let the user pick (self-service,
                                                  multi-tenant, company has 2+ IdPs)
  { mode: 'local' }                             — email + password

Resolution order (self-service): a per-user pin (the account is already
assigned to a provider) wins; then a provider whose "Email domains" list holds
the address's domain (GH #147, any install, never for an assigned account);
otherwise, on a multi-tenant install, route by the requester's company (email
domain), which may have 0, 1 or several IdPs.

Deliberately returns 'local' for unknown emails too, so this endpoint does
not reveal whether a given email has an account.

## The endpoint

| | |
|---|---|
| URL | `api/auth/resolve_login.php` |
| Protected by | Public - no sign-in check found (sign-in pages, webhooks and token links are meant to be; anything else, check the code) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 50 |  | read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 56 |  | read [analysts](/code/tables/analysts.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 84 |  | read [users](/code/tables/users.md) |  |
| 103 |  | read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [resolveTenantIdForAddress()](/code/functions/resolveTenantIdForAddress.md), [ssoPortalProviderForEmail()](/code/functions/ssoPortalProviderForEmail.md)

