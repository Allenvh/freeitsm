# inboxViewFilterSql()

The SQL fragment for a filter, against the tickets alias given.

"Mine" means ASSIGNED to me — t.assigned_analyst_id, the same column the
Analyst folders count. Not owner_id: the two are stored separately and
disagree on most rows, and "My tickets" must match the number on your own
analyst folder rather than quietly answering a different question.

"Closed" means any status flagged is_closed, not a status called "Closed" —
an install can have Resolved, Cancelled and Closed, all of them closed.
NOT EXISTS rather than NOT IN so a ticket with no status is kept: NOT IN
against a NULL status_id is NULL, and would silently drop it.

```php
function inboxViewFilterSql(array $filter, int $analystId, string $alias = 't'): string
```

Defined in [includes/inbox_view_filter.php](/code/files/includes/inbox_view_filter.php.md), line 47.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$filter` | `array` |  | array{mine:bool,hide_closed:bool} as from `inboxViewFilterFromRequest()` |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'t'` | the alias the calling query gives the tickets table (trusted - written into the SQL) |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 | `inboxViewFilterSql` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## Called from

- [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md)
- [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md)

