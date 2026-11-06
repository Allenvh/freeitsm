# api/tickets/delete_rota_entry.php

> API endpoint - 37 lines

API Endpoint: Delete a rota entry

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_rota_entry.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/rota.js](/code/files/assets/js/rota.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | **delete** [ticket_rota_entries](/code/tables/ticket_rota_entries.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

