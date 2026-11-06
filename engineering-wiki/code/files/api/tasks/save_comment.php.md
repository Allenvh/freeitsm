# api/tasks/save_comment.php

> API endpoint - 36 lines

API: Tasks — Add a comment to a task. Thin UI adapter over TasksService.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/save_comment.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

