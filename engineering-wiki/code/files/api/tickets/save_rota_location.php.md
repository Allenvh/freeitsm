# api/tickets/save_rota_location.php

> API endpoint - 71 lines

API Endpoint: Save rota location (create or update)

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_rota_location.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_ROTA_LOCATIONS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 |  | **update** [rota_locations](/code/tables/rota_locations.md) (`is_default`) |  |
| 46 |  | **update** [rota_locations](/code/tables/rota_locations.md) (`name`, `colour`, `is_default`, `display_order`, `is_active`) |  |
| 52 |  | **insert** [rota_locations](/code/tables/rota_locations.md) (`name`, `colour`, `is_default`, `display_order`, `is_active`) |  |
| 58 |  | read [rota_locations](/code/tables/rota_locations.md) |  |
| 60 |  | **update** [rota_locations](/code/tables/rota_locations.md) (`is_default`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

