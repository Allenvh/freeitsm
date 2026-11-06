# lmsRemindersDue()

Everybody who should get a reminder right now.

Walks every (learner, course) an assignment reaches, drops the ones that are
finished, have no deadline, or have already had this exact reminder, and
returns what is left with the address to send to.

🔑 THE EARLIEST DEADLINE WINS when somebody is reached twice (a people group
AND "everyone"), exactly as the learner's own page and the Progress tab
decide it. Three screens and this run must not disagree about when something
is due, or the email says one thing and the portal says another.

```php
function lmsRemindersDue(PDO $conn, array $settings): array
```

Defined in [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md), line 146.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$settings` | `array` |  | the reminder settings from `lmsReminderSettings()` |

**Returns:** array<int,array<string,mixed>>

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 157 | `lmsRemindersDue` | read [lms_courses](/code/tables/lms_courses.md), read [lms_progress](/code/tables/lms_progress.md) |  |
| 230 | `lmsRemindersDue` | read [analysts](/code/tables/analysts.md) |  |
| 236 | `lmsRemindersDue` | read [users](/code/tables/users.md) |  |
| 247 | `lmsRemindersDue` | read [lms_reminders_sent](/code/tables/lms_reminders_sent.md) |  |

## Calls

[lmsAssignedLearnersSql()](/code/functions/lmsAssignedLearnersSql.md), [lmsReminderParseDays()](/code/functions/lmsReminderParseDays.md)

## Called from

- [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md) - in [lmsRemindersRun()](/code/functions/lmsRemindersRun.md)

