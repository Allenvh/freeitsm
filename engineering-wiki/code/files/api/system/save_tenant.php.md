# api/system/save_tenant.php

> API endpoint - 101 lines

API: Create or update a company (tenant). POST JSON { id?, name, is_active }

"Company" is the user-facing word for a tenant; the underlying table/code
stays `tenants`. is_default is out of scope here and never edited.

GUARD: the default company (is_default = 1) can never be set inactive.

## The endpoint

| | |
|---|---|
| URL | `api/system/save_tenant.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 |  | read [tenants](/code/tables/tenants.md) |  |
| 84 |  | **update** [tenants](/code/tables/tenants.md) (`name`, `is_active`, `ticket_code`) |  |
| 91 |  | **insert** [tenants](/code/tables/tenants.md) (`name`, `is_default`, `is_active`, `ticket_code`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getTenantById()](/code/functions/getTenantById.md)

