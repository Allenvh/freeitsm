# api/tickets/get_users.php

> API endpoint - 180 lines

API Endpoint: Get users list Returns users with optional search filtering

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_users.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Reads | `GET search`, `GET source`, `GET limit`, `GET include_sources` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 59 |  | read [users](/code/tables/users.md), read [tickets](/code/tables/tickets.md), read [tenants](/code/tables/tenants.md), read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/users.php](/code/files/includes/users.php.md)

**Calls:** [activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md), [userDirectoryOwnedFields()](/code/functions/userDirectoryOwnedFields.md), [userSourceFilter()](/code/functions/userSourceFilter.md), [userSourcesInScope()](/code/functions/userSourcesInScope.md)

