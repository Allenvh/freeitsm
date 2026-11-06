# calendarModeIsValid()

🔑 WHY MODE IS ONE VALUE AND NOT TWO BOOLEANS.

With a push AND a subscribed feed both live, every scheduled ticket appears
TWICE in the same calendar — once as a real event and once from the
subscription. Independent switches make that trivially easy to do by accident
and baffling to diagnose. A single choice makes it unrepresentable.

```php
function calendarModeIsValid(string $mode): bool
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 34.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mode` | `string` |  | a stored or submitted calendar_enrolments.mode value - valid ones are off, push and feed |

**Returns:** `bool`

## Called from

- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md)
- [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) - in [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md)

