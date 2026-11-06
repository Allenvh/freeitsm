# api/service-status/get_service_history.php

> API endpoint - 70 lines

API: one service's status history and uptime (discussion #59).

GET ?service_id=3&days=90

Everything here is DERIVED from incidents — see includes/services/service_uptime.php
for why there is no history table to read, and for what this cannot yet see
(changes made DURING an incident).

## The endpoint

| | |
|---|---|
| URL | `api/service-status/get_service_history.php` |
| Protected by | Module access: service-status (requireModuleAccessJson) |
| Reads | `GET service_id`, `GET days` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [status_services](/code/tables/status_services.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/service_uptime.php](/code/files/includes/services/service_uptime.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

