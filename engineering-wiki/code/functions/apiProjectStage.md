# apiProjectStage()

One stage of a project (apiProjectStages), or answers 404 and stops.

```php
function apiProjectStage(PDO $conn, int $projectId, int $stageId): array
```

Defined in [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), line 330.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$stageId` | `int` |  | the project_stages id, which must belong to the project |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md), [apiProjectStages()](/code/functions/apiProjectStages.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectStagesCreate()](/code/functions/apiProjectStagesCreate.md), [apiProjectStagesUpdate()](/code/functions/apiProjectStagesUpdate.md), [apiProjectStagesDelete()](/code/functions/apiProjectStagesDelete.md), [apiProjectStagesGate()](/code/functions/apiProjectStagesGate.md)

