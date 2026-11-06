# api/system/roles.php

> API endpoint - 51 lines

System API: RBAC roles — list (GET) and create (POST).

Administrators only (managing who-can-administer-what is itself an admin act),
enforced by admin_api_guard.php. Part of RBAC Layer 2 — see docs/design/rbac.md.

## The endpoint

| | |
|---|---|
| URL | `api/system/roles.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Called from | [assets/js/system-roles.js](/code/files/assets/js/system-roles.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [rbac_roles](/code/tables/rbac_roles.md) |  |
| 26 |  | read [analysts](/code/tables/analysts.md) |  |
| 28 |  | read [rbac_role_capabilities](/code/tables/rbac_role_capabilities.md), read [rbac_analyst_roles](/code/tables/rbac_analyst_roles.md), read [rbac_team_roles](/code/tables/rbac_team_roles.md), read [rbac_roles](/code/tables/rbac_roles.md) |  |
| 43 |  | **insert** [rbac_roles](/code/tables/rbac_roles.md) (`name`, `description`, `is_active`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

