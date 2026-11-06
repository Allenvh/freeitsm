# api/tickets/get_teams.php

> API endpoint - 79 lines

API Endpoint: Get all teams

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_teams.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 21 |  | read [teams](/code/tables/teams.md) |  |
| 40 |  | read [teams](/code/tables/teams.md), read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md)

**Calls:** [analystSignInFollowerIds()](/code/functions/analystSignInFollowerIds.md), [analystSignInReady()](/code/functions/analystSignInReady.md), [analystSignInTeamMethods()](/code/functions/analystSignInTeamMethods.md), [analystSignInVerdict()](/code/functions/analystSignInVerdict.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md)

