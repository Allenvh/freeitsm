# api/projects/stage_reorder.php

> API endpoint - 11 lines

POST {project_id, ids:[...]} - put the project's time boxes in this order.

## The endpoint

| | |
|---|---|
| URL | `api/projects/stage_reorder.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md)

**Calls:** [projectApiBody()](/code/functions/projectApiBody.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRequirePost()](/code/functions/projectApiRequirePost.md), [projectApiRun()](/code/functions/projectApiRun.md)

