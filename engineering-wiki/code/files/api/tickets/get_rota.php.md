# api/tickets/get_rota.php

> API endpoint - 91 lines

API Endpoint: Get rota entries for a week GET: ?week=YYYY-MM-DD (any date in the target week; defaults to current week) Returns: analysts, shifts, entries for the Mon–Sun week containing the given date, plus the rota_include_weekends setting.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_rota.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET week` |
| Called from | [assets/js/rota.js](/code/files/assets/js/rota.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 40 |  | read [analysts](/code/tables/analysts.md) |  |
| 46 |  | read [ticket_rota_shifts](/code/tables/ticket_rota_shifts.md) |  |
| 52 |  | read [rota_locations](/code/tables/rota_locations.md) |  |
| 65 |  | read [ticket_rota_entries](/code/tables/ticket_rota_entries.md), read [ticket_rota_shifts](/code/tables/ticket_rota_shifts.md), read [rota_locations](/code/tables/rota_locations.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

