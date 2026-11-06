# api/tickets/save_ticket_task.php

> API endpoint - 109 lines

API: Attach a task to a ticket — either an existing one, or a new one created from what the analyst typed (discussion #83).

One endpoint for both because the reading pane offers one control for both.
Typing in the picker searches existing tasks and offers to create from the
same box, so "create" and "link" are the same gesture and you cannot make a
duplicate of a task that is already sitting there in the results.

POST { "ticket_id": N, "task_id": N }    → link an existing task
POST { "ticket_id": N, "title": "..." }  → create a task against the ticket

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_ticket_task.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 75 |  | read [tasks](/code/tables/tasks.md) |  |
| 89 |  | read [tickets](/code/tables/tickets.md) |  |
| 102 |  | **update** [tasks](/code/tables/tasks.md) (`ticket_id`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md)

**Calls:** [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

