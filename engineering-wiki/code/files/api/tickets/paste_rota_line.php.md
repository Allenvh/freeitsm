# api/tickets/paste_rota_line.php

> API endpoint - 175 lines

API Endpoint: paste a copied column or row over another one.

POST { kind: 'col' | 'row',
       week_start: 'YYYY-MM-DD',
       date: 'YYYY-MM-DD',          // kind = col: the day being pasted ONTO
       analyst_id: 12,              // kind = row: the analyst being pasted ONTO
       entries: [ { analyst_id | day_offset, shift_id, location_id, is_on_call } ] }
  -> { success, removed, written, skipped }

Distinct from paste_rota_cells.php, which stamps ONE shift into many cells.
A line carries a different shift per cell, so it is the week paste's
problem rather than the cell paste's, and it is solved the same way:

🔴 A LINE PASTE REPLACES THE LINE, exactly as a week paste replaces the
week. Copying Monday and pasting it onto Tuesday has to leave Tuesday
looking like Monday, which means clearing Tuesday cells Monday did not
have. Merging would produce a day matching neither, which is worse than
either.

🔴 AND "REPLACE" MUST NOT REACH PAST THE GRID. Both scopes are derived
HERE, never taken from the request:

  - ONLY ACTIVE ANALYSTS. get_rota.php lists analysts WHERE is_active = 1,
    so a deactivated analyst's entries are already invisible on the page.
  - ONLY THE DAYS ON SCREEN. With rota_include_weekends off the grid is
    Monday to Friday, and Saturday and Sunday entries exist and are simply
    not drawn. A row paste covers the drawn days and no more.

What travels in `entries` is deliberately asymmetric, and mirrors what the
paste is FOR:
  - a COLUMN is one day for every analyst, so each entry is keyed by
    ANALYST and lands on the same person on a different day;
  - a ROW is one analyst across the week, so each entry is keyed by DAY
    OFFSET and lands on the same day for a different person.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/paste_rota_line.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/rota.js](/code/files/assets/js/rota.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 68 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 76 |  | read [analysts](/code/tables/analysts.md) |  |
| 87 |  | read [ticket_rota_shifts](/code/tables/ticket_rota_shifts.md) |  |
| 89 |  | read [rota_locations](/code/tables/rota_locations.md) |  |
| 100 |  | **delete** [ticket_rota_entries](/code/tables/ticket_rota_entries.md) |  |
| 108 |  | **delete** [ticket_rota_entries](/code/tables/ticket_rota_entries.md) |  |
| 119 |  | **insert** [ticket_rota_entries](/code/tables/ticket_rota_entries.md) (`analyst_id`, `rota_date`, `shift_id`, `location_id`, `is_on_call`, `created_datetime`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

