# csUsage()

How many events and analysts depend on one connection.

```php
function csUsage(PDO $conn, int $connectionId): array
```

Defined in [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md), line 53.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$connectionId` | `int` |  | the calendar_connections id |

**Returns:** array{mapped:int, pushing:int}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 | `csUsage` | read [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |
| 57 | `csUsage` | read [calendar_enrolments](/code/tables/calendar_enrolments.md) |  |

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md)

