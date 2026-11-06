# calendarSyncConnectionIds()

The ids of every active connection, oldest first.

```php
function calendarSyncConnectionIds(PDO $conn): array
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 182.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 187 | `calendarSyncConnectionIds` | read [calendar_connections](/code/tables/calendar_connections.md) |  |

## Calls

[calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md)

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md) - in [csAdoptOrphans()](/code/functions/csAdoptOrphans.md)
- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md)
- [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) - in [calendarSyncOnlyConnection()](/code/functions/calendarSyncOnlyConnection.md), [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md)

