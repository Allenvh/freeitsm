# scheduleSchemaReady()

Does this database have the scheduling columns yet? (#1161)

Same reasoning as `snoozeSchemaReady()` immediately above, for
work_end_datetime / work_all_day: the inbox list carries the schedule so the
context menu can open Schedule on any row, and naming a column that does not
exist yet would empty the ticket list rather than merely disable a feature.

Lives here rather than in a file of its own because this is already the home
of "the busiest query must not name a column that might be missing", and one
place to look for that rule beats two.

```php
function scheduleSchemaReady(PDO $conn): bool
```

Defined in [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), line 79.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 83 | `scheduleSchemaReady` | read [tickets](/code/tables/tickets.md) |  |

## Called from

- [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md)
- [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md)
- [api/tickets/get_scheduled_tickets.php](/code/files/api/tickets/get_scheduled_tickets.php.md)

