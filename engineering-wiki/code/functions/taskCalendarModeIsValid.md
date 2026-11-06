# taskCalendarModeIsValid()

True when `$mode` is one of the task calendar modes - off, work, due or both.

```php
function taskCalendarModeIsValid(string $mode): bool
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 62.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mode` | `string` |  | a stored or submitted calendar_enrolments.task_mode value |

**Returns:** `bool`

## Called from

- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md)
- [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) - in [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md)

