# api/tasks/save_task_tag.php

> API endpoint - 55 lines

API Endpoint: Save task tag (create or update) Returns the tag id so the detail-panel picker can attach a newly created tag.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/save_task_tag.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | **update** [task_tags](/code/tables/task_tags.md) (`name`, `colour`, `display_order`) |  |
| 39 |  | **insert** [task_tags](/code/tables/task_tags.md) (`name`, `colour`, `display_order`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

