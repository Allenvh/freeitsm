# api/wiki/get_scan_history.php

> API endpoint - 42 lines

API Endpoint: Get scan run history

## The endpoint

| | |
|---|---|
| URL | `api/wiki/get_scan_history.php` |
| Protected by | Module access: wiki (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [wiki_scan_runs](/code/tables/wiki_scan_runs.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

