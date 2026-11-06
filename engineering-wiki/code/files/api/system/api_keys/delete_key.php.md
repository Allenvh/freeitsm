# api/system/api_keys/delete_key.php

> API endpoint - 37 lines

API Endpoint (internal, session): permanently delete a REST API v1 key. Its rate-limit rows cascade away with it.

## The endpoint

| | |
|---|---|
| URL | `api/system/api_keys/delete_key.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | **delete** [api_keys](/code/tables/api_keys.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

