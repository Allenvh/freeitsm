# api/v1/resources/projects.php

> API endpoint - 706 lines

FreeITSM REST API v1 — projects resource (3.2.0).

Every WRITE goes through ProjectsService / ProjectToolsService, the code the
Projects screens use, so a project changed through the API gets the same
validation, history rows, workflow events, Calendar entries and alert scan as
one changed by a person. The reads and the serialisers live here: the API
shape is a public contract, not the screens' shape.

THE KEY ACTS AS ITS ANALYST. The module's own rules apply unchanged: who may
create a project, who may change one (its team, or everyone - Projects ->
Settings), and that only the project manager, the creator or somebody who
manages Projects may delete one. A refusal is a 403 with the service's words.

COMPANY SCOPE: projects are scoped data. The list is filtered to the key's
companies, every by-id route 404s outside them (never 403, so ids cannot be
probed), and a new project lands in `company_id` (checked against the key) or
the key's default company. Sub-resources (stages, scope items, RAID, budget
lines) are reached only through their project, so they inherit its scope.

HEALTH IS WORKED OUT, never stored: `health` in a response is the health the
portfolio shows (set by hand, or worked out from tasks, dates, targets,
tickets and tolerances), with `health_mode` saying which.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/projects.php` |
| Protected by | REST API key |
| Reads | `GET status`, `GET methodology`, `GET project_manager_id`, `GET q`, `GET company_id`, `GET health`, `GET sort`, `GET type` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiProjectSelect()](/code/functions/apiProjectSelect.md) | 41 | The SELECT for projects, with owner and company names, the first active stage and the columns the exceptions are read from. |
| [apiProjectDecorate()](/code/functions/apiProjectDecorate.md) | 56 | Decorate rows (health, progress, exceptions, budget) the way the portfolio does. |
| [apiSerializeProject()](/code/functions/apiSerializeProject.md) | 69 | Shapes a decorated project row for the API - code, names, company, status, health, dates, tools, progress, active stage, exceptions and budget totals. |
| [apiLoadProject()](/code/functions/apiLoadProject.md) | 126 | The by-id gate: existence + company scope, both failing as 404. |
| [apiProjectCtx()](/code/functions/apiProjectCtx.md) | 138 | The ActorContext an API key acts as, for the Projects services. |
| [apiProjectTry()](/code/functions/apiProjectTry.md) | 147 | Run a service call; a ServiceError becomes the API's error body. |
| [apiProjectFields()](/code/functions/apiProjectFields.md) | 157 | Only the fields the service accepts, so a body cannot reach a column it should not. |
| [apiProjectsList()](/code/functions/apiProjectsList.md) | 164 | GET /projects |
| [apiProjectsGet()](/code/functions/apiProjectsGet.md) | 235 | GET /projects/{id} |
| [apiProjectsCreate()](/code/functions/apiProjectsCreate.md) | 242 | POST /projects (optionally {template: "builtin:office_move" \| "saved:12"}) |
| [apiProjectsUpdate()](/code/functions/apiProjectsUpdate.md) | 266 | PATCH /projects/{id} |
| [apiProjectsDelete()](/code/functions/apiProjectsDelete.md) | 279 | DELETE /projects/{id} - its tasks are detached, never deleted |
| [apiSerializeProjectStage()](/code/functions/apiSerializeProjectStage.md) | 290 | Stages (phases / stages / sprints) |
| [apiProjectStages()](/code/functions/apiProjectStages.md) | 315 | Every stage of a project in order, each with its count of top-level tasks and how many of those are closed. |
| [apiProjectStage()](/code/functions/apiProjectStage.md) | 330 | One stage of a project (apiProjectStages), or answers 404 and stops. |
| [apiProjectStagesList()](/code/functions/apiProjectStagesList.md) | 337 | GET /projects/{id}/stages - the project's stages in order. |
| [apiProjectStagesCreate()](/code/functions/apiProjectStagesCreate.md) | 347 | POST /projects/{id}/stages - adds a stage (name, goal, dates, status) through ProjectsService and returns it. |
| [apiProjectStagesUpdate()](/code/functions/apiProjectStagesUpdate.md) | 359 | PATCH /projects/{id}/stages/{stageId} - updates the stage fields sent through ProjectsService and returns the stage. |
| [apiProjectStagesDelete()](/code/functions/apiProjectStagesDelete.md) | 370 | DELETE /projects/{id}/stages/{stageId} - deletes a stage through ProjectsService. |
| [apiProjectStagesGate()](/code/functions/apiProjectStagesGate.md) | 379 | POST /projects/{id}/stages/{sid}/gate {decision: go\|go_with_conditions\|stop, notes?} |
| [apiSerializeProjectItem()](/code/functions/apiSerializeProjectItem.md) | 391 | Scope items (deliverables, with MoSCoW) |
| [apiProjectItem()](/code/functions/apiProjectItem.md) | 412 | One scope item of a project, or answers 404 and stops. |
| [apiProjectItemsList()](/code/functions/apiProjectItemsList.md) | 421 | GET /projects/{id}/items - the project's scope items. |
| [apiProjectItemsCreate()](/code/functions/apiProjectItemsCreate.md) | 428 | POST /projects/{id}/items - adds a scope item through ProjectToolsService and returns it. |
| [apiProjectItemsUpdate()](/code/functions/apiProjectItemsUpdate.md) | 439 | PATCH /projects/{id}/items/{itemId} - updates the scope item fields sent through ProjectToolsService and returns the item. |
| [apiProjectItemsDelete()](/code/functions/apiProjectItemsDelete.md) | 450 | DELETE /projects/{id}/items/{itemId} - deletes a scope item through ProjectToolsService. |
| [apiSerializeProjectRaid()](/code/functions/apiSerializeProjectRaid.md) | 462 | RAID log |
| [apiProjectRaidRow()](/code/functions/apiProjectRaidRow.md) | 495 | One RAID entry of a project, or answers 404 and stops. |
| [apiProjectRaidList()](/code/functions/apiProjectRaidList.md) | 507 | GET /projects/{id}/raid - the project's RAID log (risks, assumptions, issues, dependencies, decisions and lessons), optionally filtered by ?type= and ?status=. |
| [apiProjectRaidCreate()](/code/functions/apiProjectRaidCreate.md) | 517 | POST /projects/{id}/raid - adds a RAID entry through ProjectToolsService and returns it. |
| [apiProjectRaidUpdate()](/code/functions/apiProjectRaidUpdate.md) | 528 | PATCH /projects/{id}/raid/{entryId} - updates the RAID entry fields sent through ProjectToolsService and returns the entry. |
| [apiProjectRaidDelete()](/code/functions/apiProjectRaidDelete.md) | 539 | DELETE /projects/{id}/raid/{entryId} - deletes a RAID entry through ProjectToolsService. |
| [apiSerializeBudgetLine()](/code/functions/apiSerializeBudgetLine.md) | 551 | Budget |
| [apiProjectBudget()](/code/functions/apiProjectBudget.md) | 574 | GET /projects/{id}/budget - totals, labour and lines, in the project's currency. Never one person's rate. |
| [apiProjectBudgetLine()](/code/functions/apiProjectBudgetLine.md) | 604 | One budget line of a project as the analyst sees it in the budget detail, or answers 404 and stops. |
| [apiProjectBudgetLinesCreate()](/code/functions/apiProjectBudgetLinesCreate.md) | 611 | POST /projects/{id}/budget-lines - adds a budget line through ProjectToolsService and returns it. |
| [apiProjectBudgetLinesUpdate()](/code/functions/apiProjectBudgetLinesUpdate.md) | 624 | PATCH /projects/{id}/budget-lines/{lineId} - updates a budget line through ProjectToolsService and returns it. Fields the body leaves out keep their stored values. |
| [apiProjectBudgetLinesDelete()](/code/functions/apiProjectBudgetLinesDelete.md) | 641 | DELETE /projects/{id}/budget-lines/{lineId} - deletes a budget line through ProjectToolsService. |
| [apiProjectTasks()](/code/functions/apiProjectTasks.md) | 653 | GET /projects/{id}/tasks - its top-level tasks. Change them through /tasks. |
| [apiProjectLinks()](/code/functions/apiProjectLinks.md) | 676 | GET /projects/{id}/links - what it is connected to, only the kinds the key's analyst may open. |
| [apiProjectHistory()](/code/functions/apiProjectHistory.md) | 689 | GET /projects/{id}/history - newest first, paged. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 | `apiProjectSelect` | read [project_stages](/code/tables/project_stages.md) |  |
| 45 | `apiProjectSelect` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |
| 213 | `apiProjectsList` | read [projects](/code/tables/projects.md) |  |
| 316 | `apiProjectStages` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [project_stages](/code/tables/project_stages.md) |  |
| 656 | `apiProjectTasks` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [analysts](/code/tables/analysts.md) |  |
| 693 | `apiProjectHistory` | read [project_audit](/code/tables/project_audit.md) |  |
| 696 | `apiProjectHistory` | read [project_audit](/code/tables/project_audit.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md), [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md), [apiKeyDefaultTenantId()](/code/functions/apiKeyDefaultTenantId.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiPagination()](/code/functions/apiPagination.md), [apiRespond()](/code/functions/apiRespond.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [projectBudgetDetail()](/code/functions/projectBudgetDetail.md), [projectBudgetReady()](/code/functions/projectBudgetReady.md), [projectCode()](/code/functions/projectCode.md), [projectCurrencyOf()](/code/functions/projectCurrencyOf.md), [projectDecorate()](/code/functions/projectDecorate.md), [projectExceptionColumns()](/code/functions/projectExceptionColumns.md), [projectHealthConfig()](/code/functions/projectHealthConfig.md), [projectLinks()](/code/functions/projectLinks.md), [projectMethodologies()](/code/functions/projectMethodologies.md), [projectStatuses()](/code/functions/projectStatuses.md), [projectTaskStats()](/code/functions/projectTaskStats.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md), [projectVisibleTo()](/code/functions/projectVisibleTo.md)

