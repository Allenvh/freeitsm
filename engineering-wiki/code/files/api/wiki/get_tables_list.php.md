# api/wiki/get_tables_list.php

> API endpoint - 63 lines

API Endpoint: Get all database tables with reference counts

## The endpoint

| | |
|---|---|
| URL | `api/wiki/get_tables_list.php` |
| Protected by | Module access: wiki (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [wiki_scan_runs](/code/tables/wiki_scan_runs.md) |  |
| 31 |  | read [wiki_db_references](/code/tables/wiki_db_references.md), read [wiki_files](/code/tables/wiki_files.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

