# fmt_naive_date_for_email()

'YYYY-MM-DD' → '20 November 2026'. Plain, unambiguous, and not zone-shifted.

```php
function fmt_naive_date_for_email(string $ymd): string
```

Defined in [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md), line 359.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ymd` | `string` |  | a 'YYYY-MM-DD' date; anything else is returned unchanged |

**Returns:** `string`

## Called from

- [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md) - in [lmsReminderMessage()](/code/functions/lmsReminderMessage.md)

