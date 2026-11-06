# snoozeSchemaReady()

Does this database have the snooze columns yet?

The inbox list and its folder counts are the busiest queries in the product,
and they now name `snoozed_until`. On an install that has pulled this update
but not yet run Database Verify, an unguarded reference would not degrade —
it would throw "Unknown column" and leave the analyst staring at an empty
ticket list, which is a far worse outcome than not having the feature. So
every fragment below is gated on this, cached for the request.

```php
function snoozeSchemaReady(PDO $conn): bool
```

Defined in [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), line 56.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 | `snoozeSchemaReady` | read [tickets](/code/tables/tickets.md) |  |

## Called from

- [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md)
- [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md) - in [snoozeHiddenSql()](/code/functions/snoozeHiddenSql.md), [snoozeOnlySql()](/code/functions/snoozeOnlySql.md), [snoozeTicket()](/code/functions/snoozeTicket.md), [wakeTicket()](/code/functions/wakeTicket.md), [snoozeStateFor()](/code/functions/snoozeStateFor.md)

