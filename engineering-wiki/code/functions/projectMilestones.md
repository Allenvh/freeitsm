# projectMilestones()

One project's milestones in date order, each with its state, for the project page.

```php
function projectMilestones(PDO $conn, int $projectId): array
```

Defined in [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md), line 45.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 | `projectMilestones` | read [project_milestones](/code/tables/project_milestones.md), read [project_stages](/code/tables/project_stages.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[projectMilestonesReady()](/code/functions/projectMilestonesReady.md), [projectMilestoneState()](/code/functions/projectMilestoneState.md)

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) - in [projectAssistOverview()](/code/functions/projectAssistOverview.md)

