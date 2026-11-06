# api/tickets/get_department_teams.php

> API endpoint - 50 lines

API Endpoint: Get teams assigned to a department

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_department_teams.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET department_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [teams](/code/tables/teams.md), read [department_teams](/code/tables/department_teams.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

