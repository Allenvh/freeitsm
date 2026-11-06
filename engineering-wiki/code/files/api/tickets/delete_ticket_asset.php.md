# api/tickets/delete_ticket_asset.php

> API endpoint - 57 lines

API: Unlink an asset from a ticket (discussion #57). Accepts either the link row id (link_id), or a ticket_id + asset_id pair.

Mirrors delete_ticket_cmdb_object.php. The gate is on the TICKET, not the
asset: unlinking removes a row from the ticket, and an analyst who can edit
the ticket may correct what is attached to it.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_ticket_asset.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | read [ticket_assets](/code/tables/ticket_assets.md) |  |
| 41 |  | **delete** [ticket_assets](/code/tables/ticket_assets.md) |  |
| 47 |  | **delete** [ticket_assets](/code/tables/ticket_assets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

