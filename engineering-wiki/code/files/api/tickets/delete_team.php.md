# api/tickets/delete_team.php

> API endpoint - 65 lines

API Endpoint: Delete a team

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_team.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | **delete** [teams](/code/tables/teams.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md)

**Calls:** [analystSignInApplyMany()](/code/functions/analystSignInApplyMany.md), [analystSignInTeamMemberIds()](/code/functions/analystSignInTeamMemberIds.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

