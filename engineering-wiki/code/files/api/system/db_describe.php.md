# api/system/db_describe.php

> API endpoint - 141 lines

API Endpoint: Describe a single table (READ-ONLY).

Powers the detail panel on the DB Verify page: given ?table=xxx, returns the
table's LIVE structure as it exists right now — columns, indexes and foreign
keys — read straight from information_schema. It mutates nothing and is fully
decoupled from db_verify.php's schema-creation logic, so it can never mis-fire
a migration; it just reports what the database currently holds.

Admin-only: it discloses the full schema of a table, so it sits behind the
same gate as the rest of the System module (a read that exposes structure,
not a normal ticket-flow read).

## The endpoint

| | |
|---|---|
| URL | `api/system/db_describe.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `GET table` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

