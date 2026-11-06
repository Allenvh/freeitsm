# api/service-status/get_services.php

> API endpoint - 36 lines

API: Get all services for the Service Status module GET - Returns list of services ordered by display_order then name

## The endpoint

| | |
|---|---|
| URL | `api/service-status/get_services.php` |
| Protected by | Module access: service-status (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 21 |  | read [status_services](/code/tables/status_services.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

