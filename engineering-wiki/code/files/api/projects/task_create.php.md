# api/projects/task_create.php

> API endpoint - 18 lines

POST {project_id, stage_id?, title, assigned_analyst_id?, due_date?, start_date?} - a new task straight into the project, made through TasksService so it behaves exactly like one made on the Tasks board.

## The endpoint

| | |
|---|---|
| URL | `api/projects/task_create.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md)

**Calls:** [projectApiBody()](/code/functions/projectApiBody.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRequirePost()](/code/functions/projectApiRequirePost.md), [projectApiRun()](/code/functions/projectApiRun.md)

