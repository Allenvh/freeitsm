# api/tasks/save_task_priority.php

> API endpoint - 70 lines

API Endpoint: Save task priority (create or update)

## The endpoint

| | |
|---|---|
| URL | `api/tasks/save_task_priority.php` |
| Protected by | Module access: tasks (requireModuleAccessJson); Permission: Cap::TASKS_PRIORITIES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | **update** [task_priorities](/code/tables/task_priorities.md) (`is_default`) |  |
| 44 |  | **update** [task_priorities](/code/tables/task_priorities.md) (`name`, `colour`, `is_default`, `display_order`, `is_active`) |  |
| 50 |  | **insert** [task_priorities](/code/tables/task_priorities.md) (`name`, `colour`, `is_default`, `display_order`, `is_active`) |  |
| 56 |  | read [task_priorities](/code/tables/task_priorities.md) |  |
| 58 |  | **update** [task_priorities](/code/tables/task_priorities.md) (`is_default`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

