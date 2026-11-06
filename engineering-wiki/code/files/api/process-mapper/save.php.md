# api/process-mapper/save.php

> API endpoint - 167 lines

Save (create or update) a process with its steps and connectors.

For updates: replaces all steps and connectors (delete + re-insert).
Step IDs are re-assigned on save; the frontend reloads after saving.

## The endpoint

| | |
|---|---|
| URL | `api/process-mapper/save.php` |
| Protected by | Module access: process-mapper (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | **update** [processes](/code/tables/processes.md) (`title`, `updated_datetime`) |  |
| 43 |  | **insert** [processes](/code/tables/processes.md) (`title`, `created_by`, `created_datetime`, `updated_datetime`) |  |
| 50 |  | **delete** [process_connectors](/code/tables/process_connectors.md) |  |
| 51 |  | **delete** [process_steps](/code/tables/process_steps.md) |  |
| 52 |  | **delete** [process_groups](/code/tables/process_groups.md) |  |
| 53 |  | **delete** [process_lanes](/code/tables/process_lanes.md) |  |
| 56 |  | **delete** [process_annotations](/code/tables/process_annotations.md) |  |
| 60 |  | **insert** [process_lanes](/code/tables/process_lanes.md) (`process_id`, `label`, `color`, `color2`, `display_order`, `height`) |  |
| 78 |  | **insert** [process_groups](/code/tables/process_groups.md) (`process_id`, `label`, `color`, `color2`, `x`, `y`, `width`, `height`) |  |
| 99 |  | **insert** [process_steps](/code/tables/process_steps.md) (`process_id`, `type`, `label`, `description`, `url`, `x`, `y`, `width`, `height`, `color`, `color2`, `lane_id`) |  |
| 130 |  | **insert** [process_connectors](/code/tables/process_connectors.md) (`process_id`, `from_step_id`, `to_step_id`, `label`) |  |
| 144 |  | **insert** [process_annotations](/code/tables/process_annotations.md) (`process_id`, `text`, `x`, `y`, `width`, `height`, `color`, `color2`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

