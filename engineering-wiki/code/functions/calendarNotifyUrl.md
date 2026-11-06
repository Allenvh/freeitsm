# calendarNotifyUrl()

The configured notification endpoint, or '' when notifications are off.

🔑 AN ADMIN HAS TO TYPE IT. FreeITSM cannot work out what URL the outside
world can reach it on — HTTP_HOST is whatever the last request happened to
use, and behind a proxy or a tunnel it is routinely wrong. Guessing here would
produce a subscription that silently never fires.

```php
function calendarNotifyUrl(PDO $conn): string
```

Defined in [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), line 394.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 397 | `calendarNotifyUrl` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md)
- [cron/calendar_sync_pull.php](/code/files/cron/calendar_sync_pull.php.md)
- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncEnsureSubscription()](/code/functions/calendarSyncEnsureSubscription.md)

