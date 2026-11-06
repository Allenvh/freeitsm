# api/assets/get_asset_tickets.php

> API endpoint - 102 lines

API: List tickets that reference an asset (discussion #57).

Powers the Tickets tab on the asset detail page — the half of #57 that makes
the linking worth doing, because it answers "has this thing broken before?".

Mirrors api/cmdb/get_object_tickets.php, including its two lessons: gate the
two modules independently rather than trusting the same-company invariant,
and filter deleted tickets, or the tab lists tickets sitting in the recycle
bin. Two buckets:
  - open:   tickets whose status is NOT closed
  - closed: capped to the most recent 20, with a total so the UI can say
            "showing 20 of N" for a device with a long history

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_tickets.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [ticket_assets](/code/tables/ticket_assets.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [analysts](/code/tables/analysts.md), read [departments](/code/tables/departments.md) |  |
| 75 |  | read [ticket_assets](/code/tables/ticket_assets.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)

