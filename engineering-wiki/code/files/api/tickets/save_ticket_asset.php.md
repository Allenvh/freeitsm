# api/tickets/save_ticket_asset.php

> API endpoint - 89 lines

API: Link an asset to a ticket (discussion #57).

Idempotent — a repeat link returns success with already_linked, so clicking
twice is quiet rather than a SQL error.

Mirrors save_ticket_cmdb_object.php, including its hard-won lesson: reaching
both ends is not enough on a multi-company install, because an all-access
analyst can reach both. The ticket and the asset must belong to the SAME
company, or the link itself becomes the leak.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_ticket_asset.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 |  | read [tickets](/code/tables/tickets.md) |  |
| 47 |  | read [assets](/code/tables/assets.md) |  |
| 60 |  | read [tickets](/code/tables/tickets.md) |  |
| 63 |  | read [assets](/code/tables/assets.md) |  |
| 74 |  | **insert** [ticket_assets](/code/tables/ticket_assets.md) (`ticket_id`, `asset_id`, `created_datetime`, `created_by_analyst_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

