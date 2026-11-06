# api/projects/save.php

> API endpoint - 26 lines

POST - create (no id) or update (id) a project. Thin adapter over ProjectsService::saveProject(). A new project goes in the analyst's active company unless the body names another they can reach (company_id). A new project with `template` ("builtin:office_move", "saved:12") is built from that template by ProjectTemplatesService; what the form says wins.

## The endpoint

| | |
|---|---|
| URL | `api/projects/save.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md)

**Calls:** [projectApiBody()](/code/functions/projectApiBody.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRequirePost()](/code/functions/projectApiRequirePost.md), [projectApiRun()](/code/functions/projectApiRun.md), [projectApiTenantForCreate()](/code/functions/projectApiTenantForCreate.md)

