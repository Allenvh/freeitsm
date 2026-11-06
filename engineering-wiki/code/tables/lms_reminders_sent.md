# lms_reminders_sent

The fire-once record for training reminders. See freeitsm.sql — the unique key on this table is what stops a nightly reminder becoming an hourly one.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `learner_type` | `VARCHAR(10) NOT NULL` |  |
| `learner_id` | `INT NOT NULL` |  |
| `course_id` | `INT NOT NULL` |  |
| `reminder_kind` | `VARCHAR(10) NOT NULL` |  |
| `fingerprint` | `VARCHAR(100) NOT NULL` |  |
| `sent_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md):399 | `lmsRemindersRun` | insert | `learner_type`, `learner_id`, `course_id`, `reminder_kind`, `fingerprint`, `sent_datetime` |  |
| [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md):425 | `lmsRemindersRun` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md) | lmsRemindersDue L247 |  |

