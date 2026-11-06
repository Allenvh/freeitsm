# api/service-status/get_impact_levels.php

> API endpoint - 42 lines

API Endpoint: Get service impact levels

## The endpoint

| | |
|---|---|
| URL | `api/service-status/get_impact_levels.php` |
| Protected by | Module access: service-status (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

