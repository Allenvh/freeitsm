# api/tasks/list.php

> API endpoint - 318 lines

API: Tasks — List tasks with filters GET — Returns filtered task list with subtask counts Also supports ?analysts=1 and ?teams=1 for dropdown data

## The endpoint

| | |
|---|---|
| URL | `api/tasks/list.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Reads | `GET analysts`, `GET teams`, `GET filter`, `GET subtasks`, `GET team_id`, `GET analyst_id`, `GET contract_id`, `GET active_only` |
| Called from | [assets/js/tasks-table.js](/code/files/assets/js/tasks-table.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [analysts](/code/tables/analysts.md) |  |
| 32 |  | read [teams](/code/tables/teams.md) |  |
| 79 |  | read [task_collaborators](/code/tables/task_collaborators.md) |  |
| 125 |  | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [task_priorities](/code/tables/task_priorities.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md) |  |
| 169 |  | read [tasks](/code/tables/tasks.md), read [projects](/code/tables/projects.md), read [project_stages](/code/tables/project_stages.md) |  |
| 198 |  | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 223 |  | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [task_priorities](/code/tables/task_priorities.md), read [analysts](/code/tables/analysts.md) |  |
| 247 |  | read [task_tag_map](/code/tables/task_tag_map.md), read [task_tags](/code/tables/task_tags.md) |  |
| 297 |  | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md)

**Calls:** [activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [projectColourHex()](/code/functions/projectColourHex.md), [projectsSchemaReady()](/code/functions/projectsSchemaReady.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md), [tasksWithContractors()](/code/functions/tasksWithContractors.md)

