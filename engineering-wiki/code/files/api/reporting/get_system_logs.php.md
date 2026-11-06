# api/reporting/get_system_logs.php

> API endpoint - 84 lines

API Endpoint: Get system logs Returns logs filtered by type

## The endpoint

| | |
|---|---|
| URL | `api/reporting/get_system_logs.php` |
| Protected by | Module access: reporting (requireModuleAccessJson) |
| Reads | `GET type`, `GET limit`, `GET offset` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [system_logs](/code/tables/system_logs.md), read [analysts](/code/tables/analysts.md) |  |
| 58 |  | read [system_logs](/code/tables/system_logs.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

