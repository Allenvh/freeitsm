# api/tasks/save_recurrence.php

> API endpoint - 123 lines

API: Tasks — set, change or remove the recurrence on a task (#94).

POST { task_id, ...rule }   creates or updates the series this task belongs to
POST { task_id, off: true } stops it repeating, leaving every task already made

Stopping a series never deletes anything. Occurrences that have already been
created are real work, some of it done; switching the repeat off means "no
more of these", not "pretend the last six months did not happen".

## The endpoint

| | |
|---|---|
| URL | `api/tasks/save_recurrence.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [tasks](/code/tables/tasks.md) |  |
| 59 |  | **update** [task_recurrences](/code/tables/task_recurrences.md) (`is_active`, `updated_datetime`) |  |
| 96 |  | **update** [task_recurrences](/code/tables/task_recurrences.md) |  |
| 101 |  | **insert** [task_recurrences](/code/tables/task_recurrences.md) |  |
| 107 |  | **update** [tasks](/code/tables/tasks.md) (`recurrence_id`, `recurrence_master_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

