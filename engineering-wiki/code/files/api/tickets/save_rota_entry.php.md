# api/tickets/save_rota_entry.php

> API endpoint - 67 lines

API Endpoint: Create or update a rota entry

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_rota_entry.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/rota.js](/code/files/assets/js/rota.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [rota_locations](/code/tables/rota_locations.md) |  |
| 46 |  | **update** [ticket_rota_entries](/code/tables/ticket_rota_entries.md) (`shift_id`, `location_id`, `is_on_call`, `updated_datetime`) |  |
| 53 |  | **insert** [ticket_rota_entries](/code/tables/ticket_rota_entries.md) (`analyst_id`, `rota_date`, `shift_id`, `location_id`, `is_on_call`, `created_datetime`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

