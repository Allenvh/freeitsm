# api/change-management/get_calendar_changes.php

> API endpoint - 99 lines

API Endpoint: Get Calendar Changes Returns changes within a date range, optionally filtered by status

## The endpoint

| | |
|---|---|
| URL | `api/change-management/get_calendar_changes.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Reads | `GET start`, `GET end`, `GET statuses` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [changes](/code/tables/changes.md), read [change_types](/code/tables/change_types.md), read [change_statuses](/code/tables/change_statuses.md), read [change_priorities](/code/tables/change_priorities.md), read [change_impacts](/code/tables/change_impacts.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

