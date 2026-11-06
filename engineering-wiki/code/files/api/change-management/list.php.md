# api/change-management/list.php

> API endpoint - 112 lines

API Endpoint: List changes with optional filters, or return analysts list

## The endpoint

| | |
|---|---|
| URL | `api/change-management/list.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Reads | `GET analysts`, `GET status`, `GET search` |
| Called from | [assets/js/change-table.js](/code/files/assets/js/change-table.js.md), [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 23 |  | read [analysts](/code/tables/analysts.md) |  |
| 40 |  | read [changes](/code/tables/changes.md), read [change_types](/code/tables/change_types.md), read [change_statuses](/code/tables/change_statuses.md), read [change_priorities](/code/tables/change_priorities.md), read [change_impacts](/code/tables/change_impacts.md), read [analysts](/code/tables/analysts.md) |  |
| 87 |  | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

