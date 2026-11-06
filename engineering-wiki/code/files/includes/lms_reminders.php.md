# includes/lms_reminders.php

> PHP - 464 lines

Training reminders — who is due one, and sending it.

─────────────────────────────────────────────────────────────────────────────
WHY THIS EXISTS

An analyst lives in FreeITSM and sees My Courses whether anyone reminds them
or not. A self-service portal user has no reason to sign in at all — so a
course pushed to 400 staff with no email attached to it is a course 400 people
never find out about. Reminders are what makes the portal push a real feature
rather than a page nobody visits.

─────────────────────────────────────────────────────────────────────────────
🔴 OFF UNTIL SOMEBODY TURNS IT ON

The default is OFF, and deliberately so. Every other default in this file is
chosen to be safe; this one is chosen to be *inert*. Upgrading FreeITSM must
never, on its own, email several hundred people about training they were
assigned months ago — which is exactly what a helpful "default on" would do on
the first cron run after an upgrade. LMS → Settings → Reminders switches it on,
and shows how many would go out before you do.

─────────────────────────────────────────────────────────────────────────────
TWO WAYS IT RUNS, FOR THE REASON THE EXTRACTION QUEUE GIVES

  cron           cron/lms_reminders.php, once a day, the real answer
  opportunistic  a run when a manager opens the LMS, at most once an hour

A cron-only design does nothing at all on an installation that has not set one
up — which is most evaluations, plenty of shared hosts, and anybody whose
scheduled tasks are switched off. Opportunistic running means the feature
works out of the box; the cron means it is reliable. Same pattern, and the
same rationale, as includes/search/extract_queue.php.

⚠️ The opportunistic path is honest about its limits and the settings screen
says so: if nobody opens the LMS for a week, nothing is sent for a week. It is
a fallback, not a scheduler.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [lmsReminderSettings()](/code/functions/lmsReminderSettings.md) | 65 | Read the reminder settings, with their defaults applied. |
| [lmsReminderSaveSetting()](/code/functions/lmsReminderSaveSetting.md) | 98 | Write one setting. |
| [lmsReminderParseDays()](/code/functions/lmsReminderParseDays.md) | 117 | "7, 1" → [7, 1], sorted high to low, de-duplicated, nonsense discarded. |
| [lmsRemindersDue()](/code/functions/lmsRemindersDue.md) | 146 | Everybody who should get a reminder right now. |
| [lmsReminderMessage()](/code/functions/lmsReminderMessage.md) | 286 | Subject and HTML body for one reminder. |
| [lmsReminderLinkFor()](/code/functions/lmsReminderLinkFor.md) | 335 | The public address of the right training page for this kind of learner. |
| [fmt_naive_date_for_email()](/code/functions/fmt_naive_date_for_email.md) | 359 | 'YYYY-MM-DD' → '20 November 2026'. Plain, unambiguous, and not zone-shifted. |
| [lmsRemindersRun()](/code/functions/lmsRemindersRun.md) | 374 | Do a reminder run. |
| [lmsRemindersRunOpportunistic()](/code/functions/lmsRemindersRunOpportunistic.md) | 447 | The opportunistic run: called when a manager opens the LMS console. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 78 | `lmsReminderSettings` | read [system_settings](/code/tables/system_settings.md) |  |
| 100 | `lmsReminderSaveSetting` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |
| 157 | `lmsRemindersDue` | read [lms_courses](/code/tables/lms_courses.md), read [lms_progress](/code/tables/lms_progress.md) |  |
| 230 | `lmsRemindersDue` | read [analysts](/code/tables/analysts.md) |  |
| 236 | `lmsRemindersDue` | read [users](/code/tables/users.md) |  |
| 247 | `lmsRemindersDue` | read [lms_reminders_sent](/code/tables/lms_reminders_sent.md) |  |
| 340 | `lmsReminderLinkFor` | read [system_settings](/code/tables/system_settings.md) |  |
| 399 | `lmsRemindersRun` | **insert** [lms_reminders_sent](/code/tables/lms_reminders_sent.md) (`learner_type`, `learner_id`, `course_id`, `reminder_kind`, `fingerprint`, `sent_datetime`) |  |
| 425 | `lmsRemindersRun` | **delete** [lms_reminders_sent](/code/tables/lms_reminders_sent.md) |  |

## How it hangs together

**Includes:** [includes/lms_access.php](/code/files/includes/lms_access.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md)

**Included by:** [api/lms/assignments.php](/code/files/api/lms/assignments.php.md), [api/lms/reminder_settings.php](/code/files/api/lms/reminder_settings.php.md), [cron/lms_reminders.php](/code/files/cron/lms_reminders.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsAssignedLearnersSql()](/code/functions/lmsAssignedLearnersSql.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md)

