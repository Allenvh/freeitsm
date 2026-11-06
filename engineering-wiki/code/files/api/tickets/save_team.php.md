# api/tickets/save_team.php

> API endpoint - 115 lines

API Endpoint: Save (create/update) a team

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_team.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 |  | **update** [teams](/code/tables/teams.md) (`name`, `description`, `display_order`, `is_active`, `updated_datetime`) |  |
| 66 |  | **insert** [teams](/code/tables/teams.md) (`name`, `description`, `display_order`, `is_active`) |  |
| 76 |  | **update** [teams](/code/tables/teams.md) (`can_access_all_modules`) |  |
| 86 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 94 |  | **update** [teams](/code/tables/teams.md) (`auth_method`, `auth_provider_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md)

**Calls:** [analystSignInApplyMany()](/code/functions/analystSignInApplyMany.md), [analystSignInReady()](/code/functions/analystSignInReady.md), [analystSignInTeamMemberIds()](/code/functions/analystSignInTeamMemberIds.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

