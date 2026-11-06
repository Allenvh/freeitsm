# api/tasks/get_task_statuses.php

> API endpoint - 43 lines

API Endpoint: Get task statuses

## The endpoint

| | |
|---|---|
| URL | `api/tasks/get_task_statuses.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |
| Called from | [assets/js/tasks-table.js](/code/files/assets/js/tasks-table.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [task_statuses](/code/tables/task_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

