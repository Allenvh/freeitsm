# api/tickets/get_my_departments.php

> API endpoint - 82 lines

API Endpoint: Get departments accessible to the current user based on team membership

If the user has no team assignments, returns all active departments.
If the user has team assignments, returns only departments linked to those teams.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_my_departments.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 35 |  | read [departments](/code/tables/departments.md) |  |
| 44 |  | read [department_teams](/code/tables/department_teams.md), read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 58 |  | read [departments](/code/tables/departments.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

