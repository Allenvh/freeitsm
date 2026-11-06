# apiLoadProject()

The by-id gate: existence + company scope, both failing as 404.

```php
function apiLoadProject(PDO $conn, array $apiKey, int $id): array
```

Defined in [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), line 126.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the projects id |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md), [apiProjectDecorate()](/code/functions/apiProjectDecorate.md), [apiProjectSelect()](/code/functions/apiProjectSelect.md), [projectVisibleTo()](/code/functions/projectVisibleTo.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectsGet()](/code/functions/apiProjectsGet.md), [apiProjectsCreate()](/code/functions/apiProjectsCreate.md), [apiProjectsUpdate()](/code/functions/apiProjectsUpdate.md), [apiProjectsDelete()](/code/functions/apiProjectsDelete.md), [apiProjectStagesList()](/code/functions/apiProjectStagesList.md), [apiProjectStagesCreate()](/code/functions/apiProjectStagesCreate.md), [apiProjectStagesUpdate()](/code/functions/apiProjectStagesUpdate.md), [apiProjectStagesDelete()](/code/functions/apiProjectStagesDelete.md), [apiProjectStagesGate()](/code/functions/apiProjectStagesGate.md), [apiProjectItemsList()](/code/functions/apiProjectItemsList.md), [apiProjectItemsCreate()](/code/functions/apiProjectItemsCreate.md), [apiProjectItemsUpdate()](/code/functions/apiProjectItemsUpdate.md), [apiProjectItemsDelete()](/code/functions/apiProjectItemsDelete.md), [apiProjectRaidList()](/code/functions/apiProjectRaidList.md), [apiProjectRaidCreate()](/code/functions/apiProjectRaidCreate.md), [apiProjectRaidUpdate()](/code/functions/apiProjectRaidUpdate.md), [apiProjectRaidDelete()](/code/functions/apiProjectRaidDelete.md), [apiProjectBudget()](/code/functions/apiProjectBudget.md), [apiProjectBudgetLinesCreate()](/code/functions/apiProjectBudgetLinesCreate.md), [apiProjectBudgetLinesUpdate()](/code/functions/apiProjectBudgetLinesUpdate.md), [apiProjectBudgetLinesDelete()](/code/functions/apiProjectBudgetLinesDelete.md), [apiProjectTasks()](/code/functions/apiProjectTasks.md), [apiProjectLinks()](/code/functions/apiProjectLinks.md), [apiProjectHistory()](/code/functions/apiProjectHistory.md)

