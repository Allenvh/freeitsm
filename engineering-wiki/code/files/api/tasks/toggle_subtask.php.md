# api/tasks/toggle_subtask.php

> API endpoint - 132 lines

API: Tasks — Toggle subtask status between To Do and Done POST — JSON body with {id}

## The endpoint

| | |
|---|---|
| URL | `api/tasks/toggle_subtask.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 69 |  | read [task_statuses](/code/tables/task_statuses.md) |  |
| 72 |  | read [task_statuses](/code/tables/task_statuses.md) |  |
| 111 |  | **update** [tasks](/code/tables/tasks.md) (`status_id`, `completed_datetime`, `updated_datetime`) |  |
| 123 |  | **update** [tasks](/code/tables/tasks.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/checklists.php](/code/files/includes/services/checklists.php.md)

**Calls:** [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

