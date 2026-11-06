# api/tasks/search_links.php

> API endpoint - 74 lines

API: Tasks — Search tickets/changes for linking GET ?type=ticket|change&q=searchterm

## The endpoint

| | |
|---|---|
| URL | `api/tasks/search_links.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |
| Reads | `GET type`, `GET q` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | read [tickets](/code/tables/tickets.md) |  |
| 56 |  | read [changes](/code/tables/changes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)

