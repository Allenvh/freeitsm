# api/tasks/recurrence_preview.php

> API endpoint - 102 lines

API: Tasks — what a repeat rule WOULD produce, without saving it (#94).

POST { task_id, ...rule }

Reads nothing and writes nothing. It takes the settings as they currently
stand in the editor — saved or not — and hands back the dates, so somebody
can see what "every second Tuesday of the month, 5 times" actually means
before committing to it.

⚠️ The dates come from TaskRecurrence, the same engine the cron uses, and the
input is sanitised by the same TaskRecurrence::ruleFromInput() the save
endpoint uses. Working them out here — or in JavaScript, which would be
faster and is the obvious temptation — would give two implementations that
drift, and a preview that disagrees with what actually happens is worse than
no preview.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/recurrence_preview.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 |  | read [tasks](/code/tables/tasks.md) |  |
| 77 |  | read [tasks](/code/tables/tasks.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

