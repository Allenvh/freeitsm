# api/tickets/save_team_departments.php

> API endpoint - 76 lines

API Endpoint: Save departments assigned to a team (team-keyed side of the department_teams many-to-many). The department-keyed mirror is save_department_teams.php; both write the same table, from whichever side the admin happens to be editing. Used by System → Teams.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_team_departments.php` |
| Protected by | Administrators only (admin_api_guard.php); Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_DEPARTMENTS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | **delete** [department_teams](/code/tables/department_teams.md) |  |
| 50 |  | **insert** [department_teams](/code/tables/department_teams.md) (`department_id`, `team_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

