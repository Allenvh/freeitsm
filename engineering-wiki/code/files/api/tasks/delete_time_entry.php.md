# api/tasks/delete_time_entry.php

> API endpoint - 30 lines

API Endpoint: Remove a time entry from a task (GH #112). Soft delete, and only the analyst who logged it may do so.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/delete_time_entry.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

