# projectTakeBaseline()

Take a baseline of the plan as it is now. Returns its id.

```php
function projectTakeBaseline(
    PDO $conn,
    int $projectId,
    ?int $actorId,
    string $reason,
    ?string $label,
    ?int $stageId = null,
    ?int $changeId = null
): int
```

Defined in [includes/projects/control.php](/code/files/includes/projects/control.php.md), line 120.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$actorId` | `?int` |  | the analyst taking it; null or 0 records nobody (a system action) |
| `$reason` | `string` |  | why it was taken: 'manual', 'start', 'stage' or 'change' |
| `$label` | `?string` |  | the name the person gave it; null for none |
| `$stageId` | `?int` | `null` | the stage that started, for a 'stage' baseline |
| `$changeId` | `?int` | `null` | the change request applied, for a 'change' baseline |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 123 | `projectTakeBaseline` | read [project_baselines](/code/tables/project_baselines.md) |  |
| 124 | `projectTakeBaseline` | **insert** [project_baselines](/code/tables/project_baselines.md) (`project_id`, `number`, `label`, `reason`, `stage_id`, `change_request_id`, `start_date`, `target_end_date`, `budget_planned`, `currency`, `task_count`, `estimate_hours`) |  |

## Calls

[projectPlanSnapshot()](/code/functions/projectPlanSnapshot.md)

## Called from

- [includes/projects/control.php](/code/files/includes/projects/control.php.md) - in [projectBaselineAuto()](/code/functions/projectBaselineAuto.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::takeBaseline`, `ProjectToolsService::decideChangeRequest`

