# api/system/role.php

> API endpoint - 114 lines

System API: a single RBAC role — detail (GET), update (PUT), delete (DELETE).

The update writes the role's identity, its capabilities, AND its assignments
(which analysts and teams hold it) in one transaction, so the edit screen saves
as a unit. Capability keys are validated against the code registry — a key the
app doesn't define is refused, so the DB can never hold a phantom capability.

Administrators only (admin_api_guard.php). See docs/design/rbac.md.

## The endpoint

| | |
|---|---|
| URL | `api/system/role.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 |  | read [rbac_roles](/code/tables/rbac_roles.md) |  |
| 31 |  | read [rbac_role_capabilities](/code/tables/rbac_role_capabilities.md) |  |
| 35 |  | read [rbac_analyst_roles](/code/tables/rbac_analyst_roles.md) |  |
| 39 |  | read [rbac_team_roles](/code/tables/rbac_team_roles.md) |  |
| 55 |  | read [rbac_roles](/code/tables/rbac_roles.md) |  |
| 64 |  | **delete** [rbac_roles](/code/tables/rbac_roles.md) |  |
| 88 |  | **update** [rbac_roles](/code/tables/rbac_roles.md) (`name`, `description`, `is_active`, `updated_datetime`) |  |
| 92 |  | **delete** [rbac_role_capabilities](/code/tables/rbac_role_capabilities.md) |  |
| 93 |  | **insert** [rbac_role_capabilities](/code/tables/rbac_role_capabilities.md) (`role_id`, `capability_key`) |  |
| 96 |  | **delete** [rbac_analyst_roles](/code/tables/rbac_analyst_roles.md) |  |
| 97 |  | **insert** [rbac_analyst_roles](/code/tables/rbac_analyst_roles.md) (`analyst_id`, `role_id`, `created_datetime`) |  |
| 100 |  | **delete** [rbac_team_roles](/code/tables/rbac_team_roles.md) |  |
| 101 |  | **insert** [rbac_team_roles](/code/tables/rbac_team_roles.md) (`team_id`, `role_id`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [capFromKey()](/code/functions/capFromKey.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

