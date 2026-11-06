# api/tasks/reorder.php

> API endpoint - 106 lines

API: Tasks — Reorder after drag-and-drop POST — JSON body with {task_id, new_status, positions: [{id, board_position}, ...]}

## The endpoint

| | |
|---|---|
| URL | `api/tasks/reorder.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [task_statuses](/code/tables/task_statuses.md) |  |
| 55 |  | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 74 |  | **update** [tasks](/code/tables/tasks.md) (`status_id`, `updated_datetime`) |  |
| 87 |  | **update** [tasks](/code/tables/tasks.md) (`board_position`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/checklists.php](/code/files/includes/services/checklists.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

