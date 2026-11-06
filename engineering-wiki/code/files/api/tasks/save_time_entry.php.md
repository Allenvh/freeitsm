# api/tasks/save_time_entry.php

> API endpoint - 35 lines

API Endpoint: Log time against a task (GH #112). Thin UI adapter over TasksService::createTimeEntry().

## The endpoint

| | |
|---|---|
| URL | `api/tasks/save_time_entry.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

