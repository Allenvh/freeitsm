# ticketSensitivitySelectSql()

The SELECT expression for a ticket's sensitivity that is safe before Database Verification has added the column. Pass the tickets alias.

```php
function ticketSensitivitySelectSql(PDO $conn, string $alias = 't'): string
```

Defined in [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), line 325.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'t'` | the alias the calling query gives the tickets table |

**Returns:** `string`

## Calls

[ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)

## Called from

- [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md)
- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncReconcileTicket()](/code/functions/calendarSyncReconcileTicket.md)

