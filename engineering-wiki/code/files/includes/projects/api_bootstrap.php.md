# includes/projects/api_bootstrap.php

> PHP - 106 lines

Projects - the start of every api/projects/*.php endpoint.

Signed in, allowed into the module, JSON out, a connection and an actor.
Kept here rather than repeated in every endpoint so a guard cannot be
forgotten on the next one. Lives in includes/ so it cannot be requested on
its own. Mirrors includes/domains/api_bootstrap.php.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectApiBody()](/code/functions/projectApiBody.md) | 32 | The decoded JSON body (never null). |
| [projectApiOk()](/code/functions/projectApiOk.md) | 47 | Sends a success JSON reply ({success: true} plus `$data`) and ends the request. |
| [projectApiFail()](/code/functions/projectApiFail.md) | 59 | Sends a failure JSON reply ({success: false, error}) with an optional HTTP status, and ends the request. |
| [projectApiRequirePost()](/code/functions/projectApiRequirePost.md) | 67 | Writes are POST only, so a link or an image tag can never change a project. |
| [projectApiTenantForCreate()](/code/functions/projectApiTenantForCreate.md) | 78 | The company a NEW project belongs to: the one the request names (checked against what this analyst may reach), else the analyst's active company. |
| [projectApiRun()](/code/functions/projectApiRun.md) | 95 | Run a service call and turn any failure into the UI's {success:false} shape. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md)

**Included by:** [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md), [api/projects/capacity.php](/code/files/api/projects/capacity.php.md), [api/projects/delete.php](/code/files/api/projects/delete.php.md), [api/projects/export.php](/code/files/api/projects/export.php.md), [api/projects/get.php](/code/files/api/projects/get.php.md), [api/projects/links.php](/code/files/api/projects/links.php.md), [api/projects/list.php](/code/files/api/projects/list.php.md), [api/projects/lookups.php](/code/files/api/projects/lookups.php.md), [api/projects/portfolio_charts.php](/code/files/api/projects/portfolio_charts.php.md), [api/projects/reports.php](/code/files/api/projects/reports.php.md), [api/projects/save.php](/code/files/api/projects/save.php.md), [api/projects/settings.php](/code/files/api/projects/settings.php.md), [api/projects/stage_delete.php](/code/files/api/projects/stage_delete.php.md), [api/projects/stage_reorder.php](/code/files/api/projects/stage_reorder.php.md), [api/projects/stage_save.php](/code/files/api/projects/stage_save.php.md), [api/projects/task_assign.php](/code/files/api/projects/task_assign.php.md), [api/projects/task_create.php](/code/files/api/projects/task_create.php.md), [api/projects/templates.php](/code/files/api/projects/templates.php.md), [api/projects/tools.php](/code/files/api/projects/tools.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

