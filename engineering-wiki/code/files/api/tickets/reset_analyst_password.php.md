# api/tickets/reset_analyst_password.php

> API endpoint - 61 lines

API Endpoint: Reset an analyst's password

## The endpoint

| | |
|---|---|
| URL | `api/tickets/reset_analyst_password.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [analysts](/code/tables/analysts.md) |  |
| 50 |  | **update** [analysts](/code/tables/analysts.md) (`password_hash`, `last_modified_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

