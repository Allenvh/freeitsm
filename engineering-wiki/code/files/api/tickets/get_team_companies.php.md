# api/tickets/get_team_companies.php

> API endpoint - 44 lines

API Endpoint: Get the company (tenant) access a team grants its members. Returns the all-access flag + the specific granted tenant ids. Used by the "Manage companies" picker on System → Teams.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_team_companies.php` |
| Protected by | Module access: system (requireModuleAccessJson) |
| Reads | `GET team_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [teams](/code/tables/teams.md) |  |
| 32 |  | read [team_tenant_access](/code/tables/team_tenant_access.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

