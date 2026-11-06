# api/wiki/get_folder_tree.php

> API endpoint - 84 lines

API Endpoint: Get folder tree structure

## The endpoint

| | |
|---|---|
| URL | `api/wiki/get_folder_tree.php` |
| Protected by | Module access: wiki (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [wiki_scan_runs](/code/tables/wiki_scan_runs.md) |  |
| 32 |  | read [wiki_files](/code/tables/wiki_files.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

