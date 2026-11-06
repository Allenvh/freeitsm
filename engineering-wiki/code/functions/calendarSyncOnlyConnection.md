# calendarSyncOnlyConnection()

The connection when the install has exactly one, else null.

Only a fallback now: it keeps an enrolment that names no connection working
on a single-connection install - one made before connections could be
chosen, or left behind when its connection was deleted and a new one added -
without ever guessing between two.

```php
function calendarSyncOnlyConnection(PDO $conn): ?array
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 202.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Calls

[calendarSyncConnectionIds()](/code/functions/calendarSyncConnectionIds.md), [calendarSyncLoadConnection()](/code/functions/calendarSyncLoadConnection.md)

## Called from

- [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) - in [calendarSyncConnectionFor()](/code/functions/calendarSyncConnectionFor.md)
- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncRemoveRow()](/code/functions/calendarSyncRemoveRow.md)

