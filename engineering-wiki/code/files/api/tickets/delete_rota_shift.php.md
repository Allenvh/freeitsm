# api/tickets/delete_rota_shift.php

> API endpoint - 50 lines

API Endpoint: Delete a rota shift

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_rota_shift.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_ROTA (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [ticket_rota_entries](/code/tables/ticket_rota_entries.md) |  |
| 41 |  | **delete** [ticket_rota_shifts](/code/tables/ticket_rota_shifts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

