# calendarAcceptDeletes()

Off by default: whether a personal tidy-up may reach shared data is an organisation's call, and the safe answer is the one that changes nothing.

```php
function calendarAcceptDeletes(PDO $conn): bool
```

Defined in [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), line 50.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 | `calendarAcceptDeletes` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md)
- [cron/calendar_sync_pull.php](/code/files/cron/calendar_sync_pull.php.md)
- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncPullForAnalyst()](/code/functions/calendarSyncPullForAnalyst.md)

