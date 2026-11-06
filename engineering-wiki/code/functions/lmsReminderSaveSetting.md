# lmsReminderSaveSetting()

Write one setting.

```php
function lmsReminderSaveSetting(PDO $conn, string $key, string $value): void
```

Defined in [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md), line 98.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the system_settings key, one of the LMS_REM_* constants |
| `$value` | `string` |  | the value to store, as text |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 100 | `lmsReminderSaveSetting` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## Called from

- [api/lms/reminder_settings.php](/code/files/api/lms/reminder_settings.php.md)
- [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md) - in [lmsRemindersRun()](/code/functions/lmsRemindersRun.md)

