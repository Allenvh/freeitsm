# api/tickets/search_linkable_assets.php

> API endpoint - 119 lines

API: Asset search for the "Link equipment" picker on a ticket (discussion #57). GET ?ticket_id=&q=

Returns two lists. The ticket requester's own assets come back separately and
are shown first, because "my monitor is flickering" is nearly always their own
monitor — but the full estate stays searchable, because it often isn't. The
worked example from #57 is a user reporting the TV in a meeting room: nobody
holds it, so it can only ever be found by searching.

⚠️ Location is searched as well as hostname/model/serial/tag, and that is not
a nicety. Nobody knows the hostname of a meeting-room TV; they know where it
is. Without it there is nothing useful to type for shared equipment.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/search_linkable_assets.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id`, `GET q` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 |  | read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md), read [asset_locations](/code/tables/asset_locations.md) |  |
| 58 |  | read [ticket_assets](/code/tables/ticket_assets.md) |  |
| 64 |  | read [tickets](/code/tables/tickets.md) |  |
| 70 |  | read [users_assets](/code/tables/users_assets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

