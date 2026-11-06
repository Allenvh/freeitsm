# api/tickets/paste_rota_cells.php

> API endpoint - 180 lines

API Endpoint: paste one copied shift into many cells at once.

POST { shift_id, location_id, is_on_call,
       mode: 'all' | 'empty',
       targets: [ { analyst_id, rota_date } ] }
  -> { success, written, skipped_filled, skipped_invalid }

Backs three gestures on the rota, which are the same operation with a
different list of cells: a dragged selection, a whole column (one day, every
analyst) and a whole row (one analyst, every day).

ONE CALL, NOT THIRTY-FIVE, for the same reason paste_rota_week.php is one
call: firing save_rota_entry.php per cell would leave half a column pasted
behind the first failure, and the person would have no idea which half.

🔴 "EMPTY ONLY" IS DECIDED HERE, NOT BY THE CALLER. The grid the person was
looking at may be seconds old, and a colleague filling one of those cells in
the meantime is exactly the case the option exists to protect. The occupied
cells are re-read inside the transaction, so "don't overwrite anything"
means nothing that is there NOW, not nothing that was there when the page
last loaded.

Every target is checked against the same two scopes paste_rota_week.php
derives server-side, and for the same reason — the client already knows
them, but a write scoped by whatever the caller sent is scoped by whatever
the caller sent:

  - ONLY ACTIVE ANALYSTS. get_rota.php lists analysts WHERE is_active = 1.
  - ONLY THE DAYS ON SCREEN. With rota_include_weekends off, Saturday and
    Sunday entries exist and are simply not drawn; an overwrite must not
    reach them.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/paste_rota_cells.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/rota.js](/code/files/assets/js/rota.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 75 |  | read [ticket_rota_shifts](/code/tables/ticket_rota_shifts.md) |  |
| 82 |  | read [rota_locations](/code/tables/rota_locations.md) |  |
| 89 |  | read [rota_locations](/code/tables/rota_locations.md) |  |
| 92 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 96 |  | read [analysts](/code/tables/analysts.md) |  |
| 135 |  | read [ticket_rota_entries](/code/tables/ticket_rota_entries.md) |  |
| 148 |  | **insert** [ticket_rota_entries](/code/tables/ticket_rota_entries.md) (`analyst_id`, `rota_date`, `shift_id`, `location_id`, `is_on_call`, `created_datetime`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

