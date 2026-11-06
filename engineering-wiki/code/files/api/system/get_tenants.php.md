# api/system/get_tenants.php

> API endpoint - 96 lines

API: List companies (tenants). GET - returns every company. "Company" is the user-facing word for a tenant; the underlying table/code stays `tenants`.

## The endpoint

| | |
|---|---|
| URL | `api/system/get_tenants.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Reads | `GET accessible` |
| Called from | [assets/js/change-management.js](/code/files/assets/js/change-management.js.md), [assets/js/inbox.js](/code/files/assets/js/inbox.js.md), [assets/js/knowledge.js](/code/files/assets/js/knowledge.js.md), [assets/js/tasks-ctx-menu.js](/code/files/assets/js/tasks-ctx-menu.js.md), [assets/js/tasks.js](/code/files/assets/js/tasks.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [tenant_domains](/code/tables/tenant_domains.md) |  |
| 41 |  | read [tenants](/code/tables/tenants.md) |  |
| 53 |  | read [tenants](/code/tables/tenants.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getAllTenants()](/code/functions/getAllTenants.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md)

