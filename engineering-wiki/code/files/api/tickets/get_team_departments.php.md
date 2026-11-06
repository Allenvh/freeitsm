# api/tickets/get_team_departments.php

> API endpoint - 55 lines

API Endpoint: Get departments linked to a specific team

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_team_departments.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Reads | `GET team_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [departments](/code/tables/departments.md), read [department_teams](/code/tables/department_teams.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md)

