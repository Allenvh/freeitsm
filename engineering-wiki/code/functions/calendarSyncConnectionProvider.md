# calendarSyncConnectionProvider()

A connection's provider name, or '' when it does not exist. Cached per request, because it is asked for every enrolment that is read.

```php
function calendarSyncConnectionProvider(PDO $conn, ?int $connectionId): string
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 227.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$connectionId` | `?int` |  | calendar_connections.id; null or 0 gives '' |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 233 | `calendarSyncConnectionProvider` | read [calendar_connections](/code/tables/calendar_connections.md) |  |

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md)
- [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) - in [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md)

