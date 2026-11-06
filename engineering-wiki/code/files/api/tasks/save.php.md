# api/tasks/save.php

> API endpoint - 37 lines

API: Tasks — Create or update a task. Thin UI adapter over TasksService.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/save.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |
| Called from | [assets/js/tasks-table.js](/code/files/assets/js/tasks-table.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

