# api/tickets/search_linkable_tasks.php

> API endpoint - 80 lines

API: Search tasks that could be linked to a ticket (discussion #83).

Feeds the "Link to… → Task" picker on the reading pane. The picker offers
creating a task from what was typed as well as linking an existing one, and
these results are what stops the two being the same thing by accident: you see
the near matches while you type, so an existing task is linked rather than
duplicated.

Tasks already attached to THIS ticket are excluded — they are already pills on
the strip, and offering them again just invites a no-op click.

GET ?ticket_id=N&q=text

## The endpoint

| | |
|---|---|
| URL | `api/tickets/search_linkable_tasks.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id`, `GET q` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

