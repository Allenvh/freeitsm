# api/tasks/reorder_task_statuses.php

> API endpoint - 41 lines

API Endpoint: Reorder task statuses (board column order) POST { order: [statusId, statusId, ...] } — rewrites display_order to match.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/reorder_task_statuses.php` |
| Protected by | Module access: tasks (requireModuleAccessJson); Permission: Cap::TASKS_STATUSES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 |  | **update** [task_statuses](/code/tables/task_statuses.md) (`display_order`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

