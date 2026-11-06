# api/tickets/save_analyst.php

> API endpoint - 194 lines

API Endpoint: Save (create or update) an analyst

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_analyst.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 64 |  | read [analysts](/code/tables/analysts.md) |  |
| 75 |  | read [analysts](/code/tables/analysts.md) |  |
| 78 |  | read [analysts](/code/tables/analysts.md) |  |
| 100 |  | read [analysts](/code/tables/analysts.md) |  |
| 112 |  | **update** [analysts](/code/tables/analysts.md) (`username`, `full_name`, `email`, `password_hash`, `is_active`, `auth_provider_id`, `is_admin`, `last_modified_datetime`) |  |
| 126 |  | **update** [analysts](/code/tables/analysts.md) (`username`, `full_name`, `email`, `is_active`, `auth_provider_id`, `is_admin`, `last_modified_datetime`) |  |
| 144 |  | **insert** [analysts](/code/tables/analysts.md) (`username`, `password_hash`, `full_name`, `email`, `is_active`, `auth_provider_id`, `is_admin`, `created_datetime`, `last_modified_datetime`) |  |
| 154 |  | **update** [analysts](/code/tables/analysts.md) (`auth_follow_team`) |  |
| 163 |  | **update** [analysts](/code/tables/analysts.md) (`can_access_all_modules`) |  |
| 173 |  | **update** [analysts](/code/tables/analysts.md) (`can_access_all_tenants`) |  |
| 175 |  | **delete** [analyst_tenant_access](/code/tables/analyst_tenant_access.md) |  |
| 177 |  | **insert** [analyst_tenant_access](/code/tables/analyst_tenant_access.md) (`analyst_id`, `tenant_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md)

**Calls:** [analystSignInApplyMany()](/code/functions/analystSignInApplyMany.md), [analystSignInReady()](/code/functions/analystSignInReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

