# api/projects/lookups.php

> API endpoint - 65 lines

GET - what the Projects forms draw from: analysts, companies the analyst can reach, methodologies, statuses, the colour palette and icon keys, and task statuses and priorities for adding tasks.

## The endpoint

| | |
|---|---|
| URL | `api/projects/lookups.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php); Signed-in analyst (session check) (via includes/i18n.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 13 |  | read [analysts](/code/tables/analysts.md) |  |
| 26 |  | read [project_roles](/code/tables/project_roles.md) |  |
| 27 |  | read [teams](/code/tables/teams.md) |  |
| 61 |  | read [task_statuses](/code/tables/task_statuses.md) |  |
| 62 |  | read [task_priorities](/code/tables/task_priorities.md) |  |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystHasCapability()](/code/functions/analystHasCapability.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getAllTenants()](/code/functions/getAllTenants.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRun()](/code/functions/projectApiRun.md), [projectColours()](/code/functions/projectColours.md), [projectIcons()](/code/functions/projectIcons.md), [projectMethodologies()](/code/functions/projectMethodologies.md), [projectPriorities()](/code/functions/projectPriorities.md), [projectScaleLabels()](/code/functions/projectScaleLabels.md), [projectSetting()](/code/functions/projectSetting.md), [projectStatuses()](/code/functions/projectStatuses.md), [projectTemplateList()](/code/functions/projectTemplateList.md), [projectToolDefinitions()](/code/functions/projectToolDefinitions.md), [projectVisibilityReady()](/code/functions/projectVisibilityReady.md), [t()](/code/functions/t.md)

