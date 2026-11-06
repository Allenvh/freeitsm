# calendarSyncEnsureAllSubscriptions()

Every enrolled analyst's subscription, plus any left behind by someone who has since opted out.

```php
function calendarSyncEnsureAllSubscriptions(PDO $conn): array
```

Defined in [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), line 536.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 540 | `calendarSyncEnsureAllSubscriptions` | read [calendar_enrolments](/code/tables/calendar_enrolments.md) |  |

## Calls

[calendarSyncEnsureSubscription()](/code/functions/calendarSyncEnsureSubscription.md), [calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md)

## Called from

- [cron/calendar_sync_pull.php](/code/files/cron/calendar_sync_pull.php.md)

