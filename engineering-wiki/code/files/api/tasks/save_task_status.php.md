# api/tasks/save_task_status.php

> API endpoint - 71 lines

API Endpoint: Save task status (create or update)

## The endpoint

| | |
|---|---|
| URL | `api/tasks/save_task_status.php` |
| Protected by | Module access: tasks (requireModuleAccessJson); Permission: Cap::TASKS_STATUSES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | **update** [task_statuses](/code/tables/task_statuses.md) (`is_default`) |  |
| 45 |  | **update** [task_statuses](/code/tables/task_statuses.md) (`name`, `colour`, `is_closed`, `is_default`, `display_order`, `is_active`) |  |
| 51 |  | **insert** [task_statuses](/code/tables/task_statuses.md) (`name`, `colour`, `is_closed`, `is_default`, `display_order`, `is_active`) |  |
| 57 |  | read [task_statuses](/code/tables/task_statuses.md) |  |
| 59 |  | **update** [task_statuses](/code/tables/task_statuses.md) (`is_default`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

