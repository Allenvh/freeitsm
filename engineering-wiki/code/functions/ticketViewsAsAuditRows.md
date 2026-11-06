# ticketViewsAsAuditRows()

Views shaped as audit-trail rows, so the analyst's Audit window (desktop and phone alike, both fed by api/tickets/get_ticket_audit.php) shows them with no change of its own. One row per viewer per day, dated at their LAST view.

The view count goes in new_value as a plain number phrase; times are left to
created_datetime, which the browser formats in the reader's own time zone.

```php
function ticketViewsAsAuditRows(PDO $conn, int $ticketId): array
```

Defined in [includes/ticket_views.php](/code/files/includes/ticket_views.php.md), line 82.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 88 | `ticketViewsAsAuditRows` | read [ticket_views](/code/tables/ticket_views.md), read [analysts](/code/tables/analysts.md), read [users](/code/tables/users.md) |  |

## Calls

[ticketViewsReady()](/code/functions/ticketViewsReady.md)

## Called from

- [api/tickets/get_ticket_audit.php](/code/files/api/tickets/get_ticket_audit.php.md)

