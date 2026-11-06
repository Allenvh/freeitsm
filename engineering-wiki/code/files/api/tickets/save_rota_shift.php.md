# api/tickets/save_rota_shift.php

> API endpoint - 65 lines

API Endpoint: Create or update a rota shift

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_rota_shift.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_ROTA (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 47 |  | **update** [ticket_rota_shifts](/code/tables/ticket_rota_shifts.md) (`name`, `start_time`, `end_time`, `display_order`, `is_active`) |  |
| 53 |  | **insert** [ticket_rota_shifts](/code/tables/ticket_rota_shifts.md) (`name`, `start_time`, `end_time`, `display_order`, `is_active`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

