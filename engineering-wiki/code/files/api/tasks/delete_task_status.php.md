# api/tasks/delete_task_status.php

> API endpoint - 51 lines

API Endpoint: Delete task status

## The endpoint

| | |
|---|---|
| URL | `api/tasks/delete_task_status.php` |
| Protected by | Module access: tasks (requireModuleAccessJson); Permission: Cap::TASKS_STATUSES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [task_statuses](/code/tables/task_statuses.md) |  |
| 32 |  | read [tasks](/code/tables/tasks.md) |  |
| 40 |  | read [task_statuses](/code/tables/task_statuses.md) |  |
| 41 |  | **delete** [task_statuses](/code/tables/task_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

