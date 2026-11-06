# api/tickets/save_analyst_teams.php

> API endpoint - 73 lines

API Endpoint: Save teams assigned to an analyst

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_analyst_teams.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | **delete** [analyst_teams](/code/tables/analyst_teams.md) |  |
| 43 |  | **insert** [analyst_teams](/code/tables/analyst_teams.md) (`analyst_id`, `team_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md)

**Calls:** [analystSignInApplyMany()](/code/functions/analystSignInApplyMany.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

