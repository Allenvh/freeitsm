# api/service-status/get_incident_statuses.php

> API endpoint - 43 lines

API Endpoint: Get service incident statuses

## The endpoint

| | |
|---|---|
| URL | `api/service-status/get_incident_statuses.php` |
| Protected by | Module access: service-status (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

