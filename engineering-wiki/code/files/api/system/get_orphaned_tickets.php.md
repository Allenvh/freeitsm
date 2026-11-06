# api/system/get_orphaned_tickets.php

> API endpoint - 75 lines

API: list "orphaned" tickets — tickets whose department_id points at a department that no longer exists (so they're invisible to every team-filtered queue, since they're neither "no department" nor in anyone's departments). Also returns the valid active departments for the reassignment dropdowns.

## The endpoint

| | |
|---|---|
| URL | `api/system/get_orphaned_tickets.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [tickets](/code/tables/tickets.md), read [departments](/code/tables/departments.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [users](/code/tables/users.md), read [tenants](/code/tables/tenants.md) |  |
| 61 |  | read [departments](/code/tables/departments.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

