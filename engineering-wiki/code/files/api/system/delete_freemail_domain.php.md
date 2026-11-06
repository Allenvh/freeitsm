# api/system/delete_freemail_domain.php

> API endpoint - 34 lines

API: Remove a custom public / free-email domain. Built-ins aren't in this table, so they can never be removed this way. POST JSON { id }

## The endpoint

| | |
|---|---|
| URL | `api/system/delete_freemail_domain.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | **delete** [freemail_domains](/code/tables/freemail_domains.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

