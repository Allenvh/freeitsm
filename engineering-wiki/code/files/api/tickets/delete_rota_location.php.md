# api/tickets/delete_rota_location.php

> API endpoint - 52 lines

API Endpoint: Delete rota location Refuses if the location is in use by any rota entry, or if it's the default.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_rota_location.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_ROTA_LOCATIONS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | read [rota_locations](/code/tables/rota_locations.md) |  |
| 35 |  | read [ticket_rota_entries](/code/tables/ticket_rota_entries.md) |  |
| 43 |  | **delete** [rota_locations](/code/tables/rota_locations.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

