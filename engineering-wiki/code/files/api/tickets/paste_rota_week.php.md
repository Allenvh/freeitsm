# api/tickets/paste_rota_week.php

> API endpoint - 152 lines

API Endpoint: paste a copied week over another week.

POST { week_start: 'YYYY-MM-DD', entries: [ { analyst_id, day_offset, shift_id, location_id, is_on_call } ] }
  -> { success, removed, written }

ONE CALL, NOT THIRTY-FIVE. A week is up to seven days times however many
analysts you have, and firing save_rota_entry.php per cell would leave a
half-pasted week behind the first failure. This replaces the week inside a
transaction: either the week looks like the one you copied or it is untouched.

🔴 REPLACE MEANS REPLACE, AND THE SCOPE IS WHAT YOU CAN SEE. Pasting a week
has to leave the target looking like the source, which means clearing target
entries the source did not have — otherwise you get a hybrid week matching
neither, which is worse than either. But "clear the week" must never reach
beyond the grid the person was looking at:

  - ONLY ACTIVE ANALYSTS. get_rota.php lists analysts WHERE is_active = 1, so
    a deactivated analyst's entries are already invisible on the page. A
    blanket delete would destroy rows the user had no way of knowing existed.
  - ONLY THE DAYS ON SCREEN. When rota_include_weekends is off the grid is
    Monday to Friday, and Saturday and Sunday entries — which do exist, and
    which the week view simply does not draw — must survive a paste aimed at
    the working week.

Both are derived HERE rather than taken from the request: the client already
knows them, but a delete scoped by whatever the caller sent is a delete
scoped by whatever the caller sent.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/paste_rota_week.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/rota.js](/code/files/assets/js/rota.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 61 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 71 |  | read [analysts](/code/tables/analysts.md) |  |
| 84 |  | read [ticket_rota_shifts](/code/tables/ticket_rota_shifts.md) |  |
| 86 |  | read [rota_locations](/code/tables/rota_locations.md) |  |
| 91 |  | **delete** [ticket_rota_entries](/code/tables/ticket_rota_entries.md) |  |
| 99 |  | **insert** [ticket_rota_entries](/code/tables/ticket_rota_entries.md) (`analyst_id`, `rota_date`, `shift_id`, `location_id`, `is_on_call`, `created_datetime`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

