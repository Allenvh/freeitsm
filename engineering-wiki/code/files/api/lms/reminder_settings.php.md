# api/lms/reminder_settings.php

> API endpoint - 129 lines

LMS API: the training reminder settings.

GET                    read them, plus a DRY-RUN count of what would go out now
POST {action:'save'}   write them
POST {action:'test'}   send one reminder-shaped email to the caller

🔑 THE DRY RUN IS THE POINT OF THE GET. Nobody should have to switch on a mail
merge to find out how big it is. The count is produced by the same code that
does the sending, so the number on the screen is the number that will go —
a separate "estimate" query would be a second implementation and would
eventually disagree with the first.

## The endpoint

| | |
|---|---|
| URL | `api/lms/reminder_settings.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 91 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsReminderMessage()](/code/functions/lmsReminderMessage.md), [lmsReminderParseDays()](/code/functions/lmsReminderParseDays.md), [lmsReminderSaveSetting()](/code/functions/lmsReminderSaveSetting.md), [lmsReminderSettings()](/code/functions/lmsReminderSettings.md), [lmsRemindersRun()](/code/functions/lmsRemindersRun.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [ssGetSendingMailbox()](/code/functions/ssGetSendingMailbox.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md)

