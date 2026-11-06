# api/projects/stage_save.php

> API endpoint - 11 lines

POST {project_id, id?, name, goal, start_date, end_date, status} - add or edit a phase, stage or sprint.

## The endpoint

| | |
|---|---|
| URL | `api/projects/stage_save.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md)

**Calls:** [projectApiBody()](/code/functions/projectApiBody.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRequirePost()](/code/functions/projectApiRequirePost.md), [projectApiRun()](/code/functions/projectApiRun.md)

