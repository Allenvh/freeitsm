# api/tickets/clear_rota_cells.php

> API endpoint - 98 lines

API Endpoint: clear the shifts out of many rota cells at once.

POST { targets: [ { analyst_id, rota_date } ] }  ->  { success, removed }

The other half of paste_rota_cells.php: a dragged selection, a whole column
(one day, every analyst) or a whole row (one analyst, every day), emptied
in one transaction rather than N delete_rota_entry.php calls.

🔴 THIS ONE DELETES, so the scoping matters more here than anywhere else in
the rota. Both scopes are derived HERE, never taken from the request, for
the reason paste_rota_week.php spells out - a delete scoped by whatever the
caller sent is a delete scoped by whatever the caller sent:

  - ONLY ACTIVE ANALYSTS. get_rota.php lists analysts WHERE is_active = 1,
    so a deactivated analyst's entries are already invisible on the page and
    must not be destroyed by a gesture aimed at the grid.
  - ONLY THE DAYS ON SCREEN. With rota_include_weekends off the grid is
    Monday to Friday; Saturday and Sunday entries exist and are not drawn.

It also deletes ONLY the cells it was handed, one (analyst, date) pair at a
time. There is no range delete in here on purpose.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/clear_rota_cells.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/rota.js](/code/files/assets/js/rota.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 56 |  | read [analysts](/code/tables/analysts.md) |  |
| 82 |  | **delete** [ticket_rota_entries](/code/tables/ticket_rota_entries.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

