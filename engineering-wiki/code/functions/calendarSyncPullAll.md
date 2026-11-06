# calendarSyncPullAll()

Every enrolled analyst. Returns one report per person.

```php
function calendarSyncPullAll(PDO $conn): array
```

Defined in [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), line 364.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 367 | `calendarSyncPullAll` | read [calendar_enrolments](/code/tables/calendar_enrolments.md) |  |

## Calls

[calendarSyncPullForAnalyst()](/code/functions/calendarSyncPullForAnalyst.md), [calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md)

## Called from

- [cron/calendar_sync_pull.php](/code/files/cron/calendar_sync_pull.php.md)

