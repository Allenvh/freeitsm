# includes/inbox_view_filter.php

> PHP - 58 lines

The inbox's view filter (GH #149): "My tickets" and "Hide closed".

🔑 ONE HOME, because two endpoints must agree to the ticket: the folder
counts (get_ticket_counts.php) and the list (get_emails.php). If they each
spelled out "mine" or "closed" for themselves, a department badge would say
12 while the list showed 11 the first time one of them was edited — the
count-disagrees-with-list bug this inbox already carries scars from.

🔴 A FILTER, NOT A PERMISSION. Both fragments only ever narrow what the
analyst could already see; neither widens it.

Both fragments are PLACEHOLDER-FREE on purpose. The counts endpoint appends
its shared predicate to WHERE clauses and LEFT JOIN ON clauses alike, and
relies on it binding no parameters so the positional order of everything
around it is untouched. The analyst id is an int cast, never user input.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [inboxViewFilterFromRequest()](/code/functions/inboxViewFilterFromRequest.md) | 24 | Read the filter from the request. Absent means off, so every existing caller of either endpoint keeps exactly the behaviour it had. |
| [inboxViewFilterSql()](/code/functions/inboxViewFilterSql.md) | 47 | The SQL fragment for a filter, against the tickets alias given. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 | `inboxViewFilterSql` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## How it hangs together

**Included by:** [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md), [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md)

