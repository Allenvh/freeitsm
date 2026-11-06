# api/tasks/delete_task_tag.php

> API endpoint - 36 lines

API Endpoint: Delete task tag The ON DELETE CASCADE FK on task_tag_map removes it from any tasks.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/delete_task_tag.php` |
| Protected by | Module access: tasks (requireModuleAccessJson); Permission: Cap::TASKS_TAGS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 |  | read [task_tags](/code/tables/task_tags.md) |  |
| 27 |  | **delete** [task_tags](/code/tables/task_tags.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

