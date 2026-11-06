# api/tasks/reorder_subtasks.php

> API endpoint - 56 lines

API: Tasks - put a task's subtasks in a new order (dragged in the task window) POST - JSON body {parent_id, ids: [subtask id, ...]} in the new order.

The order is stored as each subtask's board_position, which get.php and the
board's subtask list already sort by. Subtasks never sit on the board as cards
of their own, so the column means nothing else for them.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/reorder_subtasks.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | **update** [tasks](/code/tables/tasks.md) (`board_position`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

