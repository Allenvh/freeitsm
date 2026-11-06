# api/wiki/get_files.php

> API endpoint - 89 lines

API Endpoint: Get file list with optional filters Query params: ?folder=api/tickets&type=PHP&search=get_users

## The endpoint

| | |
|---|---|
| URL | `api/wiki/get_files.php` |
| Protected by | Module access: wiki (requireModuleAccessJson) |
| Reads | `GET folder`, `GET type`, `GET search` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [wiki_scan_runs](/code/tables/wiki_scan_runs.md) |  |
| 36 |  | read [wiki_functions](/code/tables/wiki_functions.md), read [wiki_dependencies](/code/tables/wiki_dependencies.md), read [wiki_files](/code/tables/wiki_files.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

