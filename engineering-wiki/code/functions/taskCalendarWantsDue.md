# taskCalendarWantsDue()

Does this analyst want a task's due date in their calendar?

```php
function taskCalendarWantsDue(string $mode): bool
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 82.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mode` | `string` |  | the analyst's task mode (TASK_CAL_*), already checked by `taskCalendarModeIsValid()` |

**Returns:** `bool`

## Called from

- [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md)
- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncReconcileTask()](/code/functions/calendarSyncReconcileTask.md)

