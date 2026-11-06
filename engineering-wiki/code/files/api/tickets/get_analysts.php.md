# api/tickets/get_analysts.php

> API endpoint - 99 lines

API Endpoint: Get all analysts

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_analysts.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Reads | `GET signin` |
| Called from | [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 21 |  | read [analysts](/code/tables/analysts.md) |  |
| 33 |  | read [analyst_tenant_access](/code/tables/analyst_tenant_access.md) |  |
| 46 |  | read [analyst_teams](/code/tables/analyst_teams.md), read [teams](/code/tables/teams.md) |  |
| 49 |  | read [team_tenant_access](/code/tables/team_tenant_access.md), read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 80 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md)

**Calls:** [analystSignInReady()](/code/functions/analystSignInReady.md), [analystSignInTeamMethods()](/code/functions/analystSignInTeamMethods.md), [analystSignInVerdict()](/code/functions/analystSignInVerdict.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md)

