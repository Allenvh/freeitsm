# projectTemplateCapture()

A project as a template: its plan with dates turned into days from its start, and none of its people, companies or links. `$parts` picks what to keep: plan, scope, raid, tolerances, targets.

```php
function projectTemplateCapture(PDO $conn, array $project, array $parts): array
```

Defined in [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), line 412.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row to copy the plan from |
| `$parts` | `array` |  | what to keep: any of 'plan', 'scope', 'raid', 'benefits', 'tolerances', 'targets' |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 418 | `projectTemplateCapture` | read [project_stages](/code/tables/project_stages.md) |  |
| 432 | `projectTemplateCapture` | read [project_stages](/code/tables/project_stages.md) |  |
| 434 | `projectTemplateCapture` | read [tasks](/code/tables/tasks.md) |  |
| 446 | `projectTemplateCapture` | read [project_gate_items](/code/tables/project_gate_items.md) |  |
| 464 | `projectTemplateCapture` | read [task_dependencies](/code/tables/task_dependencies.md) |  |
| 471 | `projectTemplateCapture` | read [project_milestones](/code/tables/project_milestones.md) |  |
| 479 | `projectTemplateCapture` | read [project_items](/code/tables/project_items.md) |  |
| 487 | `projectTemplateCapture` | read [project_raid](/code/tables/project_raid.md) |  |
| 495 | `projectTemplateCapture` | read [project_benefits](/code/tables/project_benefits.md) |  |
| 508 | `projectTemplateCapture` | read [project_asset_targets](/code/tables/project_asset_targets.md) |  |

## Calls

[projectBenefitsReady()](/code/functions/projectBenefitsReady.md), [projectDependenciesReady()](/code/functions/projectDependenciesReady.md), [projectEstimatesReady()](/code/functions/projectEstimatesReady.md), [projectGateItemsReady()](/code/functions/projectGateItemsReady.md), [projectGateKindReady()](/code/functions/projectGateKindReady.md), [projectMilestonesReady()](/code/functions/projectMilestonesReady.md), [projectTargetsReady()](/code/functions/projectTargetsReady.md), [projectTargetValueNames()](/code/functions/projectTargetValueNames.md), [projectTemplateNormalise()](/code/functions/projectTemplateNormalise.md)

## Called from

- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::saveFromProject`

