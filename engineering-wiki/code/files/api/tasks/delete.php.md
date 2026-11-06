# api/tasks/delete.php

> API endpoint - 27 lines

API: Tasks — Delete a task and its subtasks. Thin UI adapter over TasksService.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/delete.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

