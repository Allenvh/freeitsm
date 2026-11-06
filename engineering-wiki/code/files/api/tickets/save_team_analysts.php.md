# api/tickets/save_team_analysts.php

> API endpoint - 79 lines

API Endpoint: Save analysts assigned to a team (team-keyed side of the analyst_teams many-to-many). The analyst-keyed mirror is save_analyst_teams.php; both write the same table, from whichever side the admin happens to be editing. Used by System → Teams.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_team_analysts.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | **delete** [analyst_teams](/code/tables/analyst_teams.md) |  |
| 50 |  | **insert** [analyst_teams](/code/tables/analyst_teams.md) (`analyst_id`, `team_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md)

**Calls:** [analystSignInApplyMany()](/code/functions/analystSignInApplyMany.md), [analystSignInTeamMemberIds()](/code/functions/analystSignInTeamMemberIds.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

