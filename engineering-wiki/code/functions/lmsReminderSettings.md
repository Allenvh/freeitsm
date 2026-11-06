# lmsReminderSettings()

Read the reminder settings, with their defaults applied.

```php
function lmsReminderSettings(PDO $conn): array
```

Defined in [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md), line 65.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 78 | `lmsReminderSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/lms/reminder_settings.php](/code/files/api/lms/reminder_settings.php.md)
- [cron/lms_reminders.php](/code/files/cron/lms_reminders.php.md)
- [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md) - in [lmsRemindersRun()](/code/functions/lmsRemindersRun.md), [lmsRemindersRunOpportunistic()](/code/functions/lmsRemindersRunOpportunistic.md)

