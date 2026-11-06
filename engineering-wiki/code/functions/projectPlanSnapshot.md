# projectPlanSnapshot()

The plan as it is now, in the shape a baseline stores: the headline figures plus the detail, so the same function makes a baseline and the "now" side of every comparison.

```php
function projectPlanSnapshot(PDO $conn, int $projectId): array
```

Defined in [includes/projects/control.php](/code/files/includes/projects/control.php.md), line 54.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 56 | `projectPlanSnapshot` | read [projects](/code/tables/projects.md) |  |
| 60 | `projectPlanSnapshot` | read [tasks](/code/tables/tasks.md) |  |
| 63 | `projectPlanSnapshot` | read [tasks](/code/tables/tasks.md) |  |
| 67 | `projectPlanSnapshot` | read [project_stages](/code/tables/project_stages.md) |  |
| 73 | `projectPlanSnapshot` | read [project_milestones](/code/tables/project_milestones.md) |  |
| 80 | `projectPlanSnapshot` | read [project_items](/code/tables/project_items.md) |  |
| 87 | `projectPlanSnapshot` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |

## Called from

- [includes/demo_data.php](/code/files/includes/demo_data.php.md) - in [demoAfterImport()](/code/functions/demoAfterImport.md)
- [includes/projects/control.php](/code/files/includes/projects/control.php.md) - in [projectTakeBaseline()](/code/functions/projectTakeBaseline.md), [projectControlDetail()](/code/functions/projectControlDetail.md)

