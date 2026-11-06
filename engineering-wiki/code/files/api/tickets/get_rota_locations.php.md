# api/tickets/get_rota_locations.php

> API endpoint - 42 lines

API Endpoint: Get rota locations

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_rota_locations.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [rota_locations](/code/tables/rota_locations.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

