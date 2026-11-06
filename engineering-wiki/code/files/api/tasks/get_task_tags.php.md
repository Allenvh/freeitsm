# api/tasks/get_task_tags.php

> API endpoint - 33 lines

API Endpoint: Get task tags

## The endpoint

| | |
|---|---|
| URL | `api/tasks/get_task_tags.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 19 |  | read [task_tags](/code/tables/task_tags.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

