# api/projects/delete.php

> API endpoint - 13 lines

POST {id} - delete a project. Its tasks are kept and taken out of the project; its stages and history are removed with it.

## The endpoint

| | |
|---|---|
| URL | `api/projects/delete.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md)

**Calls:** [projectApiBody()](/code/functions/projectApiBody.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRequirePost()](/code/functions/projectApiRequirePost.md), [projectApiRun()](/code/functions/projectApiRun.md)

