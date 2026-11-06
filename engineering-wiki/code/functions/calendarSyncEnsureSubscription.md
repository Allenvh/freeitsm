# calendarSyncEnsureSubscription()

Bring one analyst's subscription into line: create, renew or remove.

Idempotent, and safe to call every cron run — it does nothing at all unless
something needs doing.

```php
function calendarSyncEnsureSubscription(PDO $conn, int $analystId): string
```

Defined in [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), line 417.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** string what happened, for the cron's output

## Calls

[calendarClearSubscription()](/code/functions/calendarClearSubscription.md), [calendarNotifyUrl()](/code/functions/calendarNotifyUrl.md), [calendarStoreSubscription()](/code/functions/calendarStoreSubscription.md), [calendarSyncConnectionFor()](/code/functions/calendarSyncConnectionFor.md), [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md), [calendarSyncProviderFor()](/code/functions/calendarSyncProviderFor.md), [calendarSyncRecordError()](/code/functions/calendarSyncRecordError.md)

## Called from

- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncEnsureAllSubscriptions()](/code/functions/calendarSyncEnsureAllSubscriptions.md)

