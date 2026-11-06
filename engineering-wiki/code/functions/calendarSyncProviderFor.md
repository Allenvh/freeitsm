# calendarSyncProviderFor()

Build the provider for a connection.

⚠️ Throws on an unknown provider rather than returning null. A connection row
naming a provider this build does not have is a misconfiguration, and pushing
silently to nowhere is the worst possible response to it.

```php
function calendarSyncProviderFor(array $connection, ?array $enrolment = null): CalendarSyncProvider
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 257.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$connection` | `array` |  | a calendar_connections row with credentials decrypted, from `calendarSyncLoadConnection()` |
| `$enrolment` | `?array` | `null` | the enrolment of the analyst whose calendar is being touched. Microsoft ignores it; CalDAV signs in with the credentials on it (see CalendarSyncProvider::`$account`). Pass it whenever an analyst is involved - a CalDAV provider built without one refuses every call with an explanation rather than guessing. |

**Returns:** `CalendarSyncProvider`

## Calls

[calendarSyncAccount()](/code/functions/calendarSyncAccount.md)

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md) - in [csTakeBack()](/code/functions/csTakeBack.md)
- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md) - in [enrolSwitchConnection()](/code/functions/enrolSwitchConnection.md)
- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncPullForAnalyst()](/code/functions/calendarSyncPullForAnalyst.md), [calendarSyncEnsureSubscription()](/code/functions/calendarSyncEnsureSubscription.md)
- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncReconcileTicket()](/code/functions/calendarSyncReconcileTicket.md), [calendarSyncReconcileTask()](/code/functions/calendarSyncReconcileTask.md), [calendarSyncRemoveRow()](/code/functions/calendarSyncRemoveRow.md)

