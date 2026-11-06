# lmsReminderParseDays()

"7, 1" → [7, 1], sorted high to low, de-duplicated, nonsense discarded.

⚠️ 0 IS ALLOWED AND MEANS "on the day it is due". Negative numbers are not:
a reminder "-3 days before" is the overdue chase, which is a separate setting
with its own repeat, and accepting it here would send two different emails
about the same day.

```php
function lmsReminderParseDays(string $raw): array
```

Defined in [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md), line 117.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the "days before" setting as typed, e.g. '7, 1'; numbers outside 0-365 are dropped |

**Returns:** `array`

## Called from

- [api/lms/reminder_settings.php](/code/files/api/lms/reminder_settings.php.md)
- [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md) - in [lmsRemindersDue()](/code/functions/lmsRemindersDue.md)

