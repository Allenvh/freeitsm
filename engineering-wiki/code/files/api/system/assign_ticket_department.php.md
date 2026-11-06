# api/system/assign_ticket_department.php

> API endpoint - 87 lines

API: reassign one or more tickets to a department (or to "no department"). Used by the System -> Orphaned tickets screen to fix tickets pointing at a department that no longer exists.

POST JSON { ticket_ids: [int,...], department_id: int|null }
  department_id null/empty => clears it (sets to NULL = "no department").

## The endpoint

| | |
|---|---|
| URL | `api/system/assign_ticket_department.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 |  | read [departments](/code/tables/departments.md) |  |
| 53 |  | read [tickets](/code/tables/tickets.md) |  |
| 58 |  | **update** [tickets](/code/tables/tickets.md) (`department_id`, `updated_datetime`) |  |
| 64 |  | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md)

