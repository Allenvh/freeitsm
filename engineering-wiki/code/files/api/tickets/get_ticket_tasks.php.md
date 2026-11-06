# api/tickets/get_ticket_tasks.php

> API endpoint - 85 lines

API: List the tasks linked to a ticket (discussion #83).

Returns what the reading pane's Links strip needs for a task pill: the title,
the status and its colour, whether that status closes the task, who it is
assigned to, and the subtask progress — #83 asked to see "task status and
completion progress" without leaving the ticket.

The link is `tasks.ticket_id`, a column on the task rather than a join table,
so a task belongs to at most one ticket while a ticket may have any number.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_tasks.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

