# apiProjectCtx()

The ActorContext an API key acts as, for the Projects services.

```php
function apiProjectCtx(array $apiKey): ActorContext
```

Defined in [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), line 138.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `ActorContext`

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectsCreate()](/code/functions/apiProjectsCreate.md), [apiProjectsUpdate()](/code/functions/apiProjectsUpdate.md), [apiProjectsDelete()](/code/functions/apiProjectsDelete.md), [apiProjectStagesCreate()](/code/functions/apiProjectStagesCreate.md), [apiProjectStagesUpdate()](/code/functions/apiProjectStagesUpdate.md), [apiProjectStagesDelete()](/code/functions/apiProjectStagesDelete.md), [apiProjectStagesGate()](/code/functions/apiProjectStagesGate.md), [apiProjectItemsCreate()](/code/functions/apiProjectItemsCreate.md), [apiProjectItemsUpdate()](/code/functions/apiProjectItemsUpdate.md), [apiProjectItemsDelete()](/code/functions/apiProjectItemsDelete.md), [apiProjectRaidCreate()](/code/functions/apiProjectRaidCreate.md), [apiProjectRaidUpdate()](/code/functions/apiProjectRaidUpdate.md), [apiProjectRaidDelete()](/code/functions/apiProjectRaidDelete.md), [apiProjectBudgetLinesCreate()](/code/functions/apiProjectBudgetLinesCreate.md), [apiProjectBudgetLinesUpdate()](/code/functions/apiProjectBudgetLinesUpdate.md), [apiProjectBudgetLinesDelete()](/code/functions/apiProjectBudgetLinesDelete.md), [apiProjectLinks()](/code/functions/apiProjectLinks.md)

