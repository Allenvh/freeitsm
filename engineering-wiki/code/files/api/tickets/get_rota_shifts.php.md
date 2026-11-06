# api/tickets/get_rota_shifts.php

> API endpoint - 32 lines

API Endpoint: Get all rota shifts

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_rota_shifts.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [ticket_rota_shifts](/code/tables/ticket_rota_shifts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

