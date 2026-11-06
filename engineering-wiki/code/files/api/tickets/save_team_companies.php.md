# api/tickets/save_team_companies.php

> API endpoint - 53 lines

API Endpoint: Save the company (tenant) access a team grants its members.

Team grants are ADDITIVE — they widen the access of every analyst in the team
(unioned with each analyst's own grants in `getAccessibleTenantIds()`). A team
defaults to granting nothing; set can_access_all_tenants for every company, or
list specific companies. Used by System → Teams.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_team_companies.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 |  | **update** [teams](/code/tables/teams.md) (`can_access_all_tenants`) |  |
| 39 |  | **delete** [team_tenant_access](/code/tables/team_tenant_access.md) |  |
| 41 |  | **insert** [team_tenant_access](/code/tables/team_tenant_access.md) (`team_id`, `tenant_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

