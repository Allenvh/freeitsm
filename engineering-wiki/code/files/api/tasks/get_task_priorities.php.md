# api/tasks/get_task_priorities.php

> API endpoint - 42 lines

API Endpoint: Get task priorities

## The endpoint

| | |
|---|---|
| URL | `api/tasks/get_task_priorities.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |
| Called from | [assets/js/tasks-table.js](/code/files/assets/js/tasks-table.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [task_priorities](/code/tables/task_priorities.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

