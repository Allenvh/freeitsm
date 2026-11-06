# api/tasks/get_time_entries.php

> API endpoint - 42 lines

API Endpoint: Time entries for a task, with totals (GH #112). Thin UI adapter over TasksService::timeEntriesFor().

Returns both totals: this task alone, and the roll-up including its subtasks —
the latter being the number that answers "how long did this take" once a piece
of work has been broken up.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/get_time_entries.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |
| Reads | `GET task_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [tasks](/code/tables/tasks.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

