# api/tickets/get_ticket_assets.php

> API endpoint - 62 lines

API: List the assets linked to a ticket (discussion #57).

Returns everything the reading pane's card needs in one call — the fields
discussion #57 asked to see without leaving the ticket: asset tag, make and
model, serial number, warranty, type and location.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_assets.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [ticket_assets](/code/tables/ticket_assets.md), read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md), read [asset_locations](/code/tables/asset_locations.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

