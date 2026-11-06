# api/wiki/get_table_references.php

> API endpoint - 57 lines

API Endpoint: Get files referencing a specific database table Query param: ?table=tickets

## The endpoint

| | |
|---|---|
| URL | `api/wiki/get_table_references.php` |
| Protected by | Module access: wiki (requireModuleAccessJson) |
| Reads | `GET table` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [wiki_scan_runs](/code/tables/wiki_scan_runs.md) |  |
| 38 |  | read [wiki_db_references](/code/tables/wiki_db_references.md), read [wiki_files](/code/tables/wiki_files.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

