# api/tickets/get_analyst_teams.php

> API endpoint - 50 lines

API Endpoint: Get teams assigned to an analyst

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_analyst_teams.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Reads | `GET analyst_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [teams](/code/tables/teams.md), read [analyst_teams](/code/tables/analyst_teams.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md)

