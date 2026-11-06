# api/projects/task_assign.php

> API endpoint - 16 lines

POST {task_id, project_id|null, stage_id?} - put an existing task in a project (and stage), move it to another stage, or take it out (project_id null).

## The endpoint

| | |
|---|---|
| URL | `api/projects/task_assign.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php) |
| Called from | [assets/js/tasks.js](/code/files/assets/js/tasks.js.md) |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md)

**Calls:** [projectApiBody()](/code/functions/projectApiBody.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRequirePost()](/code/functions/projectApiRequirePost.md), [projectApiRun()](/code/functions/projectApiRun.md)

