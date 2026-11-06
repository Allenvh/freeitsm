# api/tickets/save_department_teams.php

> API endpoint - 70 lines

API Endpoint: Save teams assigned to a department

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_department_teams.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_DEPARTMENTS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | **delete** [department_teams](/code/tables/department_teams.md) |  |
| 44 |  | **insert** [department_teams](/code/tables/department_teams.md) (`department_id`, `team_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

