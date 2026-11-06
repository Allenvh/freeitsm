# projectControlDetail()

Everything the Change control tab shows. null before Database Verification.

```php
function projectControlDetail(PDO $conn, array $project, int $analystId): ?array
```

Defined in [includes/projects/control.php](/code/files/includes/projects/control.php.md), line 310.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 315 | `projectControlDetail` | read [project_baselines](/code/tables/project_baselines.md), read [analysts](/code/tables/analysts.md), read [project_stages](/code/tables/project_stages.md), read [project_change_requests](/code/tables/project_change_requests.md) |  |
| 333 | `projectControlDetail` | read [project_change_requests](/code/tables/project_change_requests.md), read [analysts](/code/tables/analysts.md), read [project_baselines](/code/tables/project_baselines.md) |  |

## Calls

[projectBaselineShape()](/code/functions/projectBaselineShape.md), [projectBaselineVariance()](/code/functions/projectBaselineVariance.md), [projectCanChange()](/code/functions/projectCanChange.md), [projectCanDecideChange()](/code/functions/projectCanDecideChange.md), [projectControlReady()](/code/functions/projectControlReady.md), [projectPlanSnapshot()](/code/functions/projectPlanSnapshot.md), [projectSetting()](/code/functions/projectSetting.md)

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)

