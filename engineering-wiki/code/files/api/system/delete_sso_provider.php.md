# api/system/delete_sso_provider.php

> API endpoint - 72 lines

API: Delete an SSO / OIDC identity provider. POST JSON { id }

Linked rows in analyst_sso_identities are removed by the ON DELETE CASCADE
foreign key; any analyst whose auth_provider_id pointed here is reset to
local (ON DELETE SET NULL).

## The endpoint

| | |
|---|---|
| URL | `api/system/delete_sso_provider.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | **delete** [carddav_write_log](/code/tables/carddav_write_log.md) |  |
| 50 |  | **delete** [auth_providers](/code/tables/auth_providers.md) |  |
| 57 |  | **update** [analysts](/code/tables/analysts.md) (`auth_provider_id`) |  |
| 59 |  | **update** [teams](/code/tables/teams.md) (`auth_method`, `auth_provider_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md)

**Calls:** [analystSignInApplyMany()](/code/functions/analystSignInApplyMany.md), [analystSignInFollowerIds()](/code/functions/analystSignInFollowerIds.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

