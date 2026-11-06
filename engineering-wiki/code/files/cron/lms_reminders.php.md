# cron/lms_reminders.php

> PHP - 82 lines

Training reminders — cron entry point.

Emails the people whose assigned training is nearly due, or overdue, according
to LMS → Settings → Reminders. Does nothing at all until reminders are
switched on there.

ONCE A DAY is the right schedule. The work is driven by "how many days until
the deadline", which does not change within a day, so running it hourly sends
nothing extra — the fire-once table sees to that — and simply asks the
database the same question twenty-four times.

SECURITY (HTTP invocation only): a shared-secret token in ?token=, compared
against `lms_reminder_cron_token` in system_settings with `hash_equals()`. Same
shape as the SLA cron, minus its per-IP lockout and run log: this job sends
email that is idempotent and rate-limited by its own dedup table, so the worst
a leaked token buys is an early run of something that was going to happen
anyway. CLI invocation needs no token.

  Linux   0 8 * * *  /usr/bin/php /path/to/cron/lms_reminders.php
  Windows Task Scheduler, daily, running php.exe with the full script path
  HTTP    https://your-freeitsm/cron/lms_reminders.php?token=…

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsReminderSettings()](/code/functions/lmsReminderSettings.md), [lmsRemindersRun()](/code/functions/lmsRemindersRun.md)

