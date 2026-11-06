# api/tasks/get.php

> API endpoint - 187 lines

API: Tasks — Get single task with subtasks and comments GET ?id=N

## The endpoint

| | |
|---|---|
| URL | `api/tasks/get.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [task_priorities](/code/tables/task_priorities.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md), read [tickets](/code/tables/tickets.md), read [changes](/code/tables/changes.md) |  |
| 87 |  | read [projects](/code/tables/projects.md), read [project_stages](/code/tables/project_stages.md) |  |
| 100 |  | read [tasks](/code/tables/tasks.md) |  |
| 107 |  | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [task_priorities](/code/tables/task_priorities.md), read [analysts](/code/tables/analysts.md) |  |
| 125 |  | read [task_comments](/code/tables/task_comments.md), read [analysts](/code/tables/analysts.md) |  |
| 136 |  | read [task_tag_map](/code/tables/task_tag_map.md), read [task_tags](/code/tables/task_tags.md) |  |
| 155 |  | read [task_recurrences](/code/tables/task_recurrences.md) |  |
| 162 |  | read [tasks](/code/tables/tasks.md) |  |
| 170 |  | read [tasks](/code/tables/tasks.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/entity_links.php](/code/files/includes/entity_links.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md), [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md)

**Calls:** [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [entityLink()](/code/functions/entityLink.md), [projectColourHex()](/code/functions/projectColourHex.md), [projectsSchemaReady()](/code/functions/projectsSchemaReady.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [tasksWithContractors()](/code/functions/tasksWithContractors.md)

