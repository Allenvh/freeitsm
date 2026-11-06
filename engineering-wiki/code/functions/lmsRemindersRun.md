# lmsRemindersRun()

Do a reminder run.

```php
function lmsRemindersRun(PDO $conn, bool $dryRun = false): array
```

Defined in [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md), line 374.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$dryRun` | `bool` | `false` | true to work out what WOULD be sent and send nothing — which is what the settings screen shows before you switch the feature on. Nobody should have to turn on a mail merge to find out how big it is. |

**Returns:** array{would:int,sent:int,skipped:int,unreachable:int,failed:int,capped:bool}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 399 | `lmsRemindersRun` | **insert** [lms_reminders_sent](/code/tables/lms_reminders_sent.md) (`learner_type`, `learner_id`, `course_id`, `reminder_kind`, `fingerprint`, `sent_datetime`) |  |
| 425 | `lmsRemindersRun` | **delete** [lms_reminders_sent](/code/tables/lms_reminders_sent.md) |  |

## Calls

[lmsReminderMessage()](/code/functions/lmsReminderMessage.md), [lmsReminderSaveSetting()](/code/functions/lmsReminderSaveSetting.md), [lmsRemindersDue()](/code/functions/lmsRemindersDue.md), [lmsReminderSettings()](/code/functions/lmsReminderSettings.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md)

## Called from

- [api/lms/reminder_settings.php](/code/files/api/lms/reminder_settings.php.md)
- [cron/lms_reminders.php](/code/files/cron/lms_reminders.php.md)
- [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md) - in [lmsRemindersRunOpportunistic()](/code/functions/lmsRemindersRunOpportunistic.md)

