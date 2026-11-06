# api/tickets/delete_ticket_task.php

> API endpoint - 56 lines

API: Unlink a task from a ticket (discussion #83).

⚠️ Unlinks, never deletes. The link is `tasks.ticket_id`, so removing it just
clears that column and the task carries on living in the Tasks module. The ✕
on a pill in the ticket's Links strip means "this is not related to this
ticket", not "throw this work away" — deleting is done from the task itself,
where the consequences are visible.

The task keeps its company. It was stamped when the task was created and does
not follow the link around; a task that stops belonging to a ticket does not
thereby stop belonging to the client.

POST { "ticket_id": N, "task_id": N }

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_ticket_task.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 |  | **update** [tasks](/code/tables/tasks.md) (`ticket_id`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

