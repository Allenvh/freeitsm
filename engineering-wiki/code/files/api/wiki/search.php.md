# api/wiki/search.php

> API endpoint - 84 lines

API Endpoint: Global search across files, functions, and tables Query params: ?q=searchterm&type=all|files|functions|tables

## The endpoint

| | |
|---|---|
| URL | `api/wiki/search.php` |
| Protected by | Module access: wiki (requireModuleAccessJson) |
| Reads | `GET q`, `GET type` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 29 |  | read [wiki_scan_runs](/code/tables/wiki_scan_runs.md) |  |
| 44 |  | read [wiki_files](/code/tables/wiki_files.md) |  |
| 54 |  | read [wiki_functions](/code/tables/wiki_functions.md), read [wiki_files](/code/tables/wiki_files.md) |  |
| 66 |  | read [wiki_db_references](/code/tables/wiki_db_references.md), read [wiki_files](/code/tables/wiki_files.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

