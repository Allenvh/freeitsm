# api/wiki/get_stats.php

> API endpoint - 66 lines

API Endpoint: Get wiki dashboard statistics

## The endpoint

| | |
|---|---|
| URL | `api/wiki/get_stats.php` |
| Protected by | Module access: wiki (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 21 |  | read [wiki_scan_runs](/code/tables/wiki_scan_runs.md) |  |
| 33 |  | read [wiki_db_references](/code/tables/wiki_db_references.md), read [wiki_files](/code/tables/wiki_files.md) |  |
| 37 |  | read [wiki_files](/code/tables/wiki_files.md) |  |
| 40 |  | read [wiki_files](/code/tables/wiki_files.md) |  |
| 44 |  | read [wiki_files](/code/tables/wiki_files.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

