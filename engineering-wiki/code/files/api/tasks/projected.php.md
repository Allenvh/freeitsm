# api/tasks/projected.php

> API endpoint - 48 lines

API: Tasks — future occurrences of fixed-schedule repeats (#94).

GET ?from=YYYY-MM-DD&to=YYYY-MM-DD

Returns dates a repeat WILL land on, computed from the rule. Nothing is
created and nothing is written; these are drawn faintly on the calendar so a
plan is visible before the work exists.

Only fixed schedules appear here. A repeat that fires on completion has no
predictable future — its next date is counted from the day somebody finishes
the current one — so projecting it would be inventing information.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/projected.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |
| Reads | `GET from`, `GET to` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

