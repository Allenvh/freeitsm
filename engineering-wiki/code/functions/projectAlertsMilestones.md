# projectAlertsMilestones()

Milestones (3.3.0), on the same ledger as stage ends: project.milestone_due 7 days before, then the day before (nearest window only) project.milestone_missed once, the first scan after its date passes without it being reached. Moving the date re-arms both - the fingerprint is the due date. A milestone that was already past when it was ADDED is not news to anybody, so "missed" only fires for dates in the last 14 days.

```php
function projectAlertsMilestones(PDO $conn, ?int $projectId = null): int
```

Defined in [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), line 328.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `?int` | `null` | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 334 | `projectAlertsMilestones` | read [project_milestones](/code/tables/project_milestones.md), read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 349 | `projectAlertsMilestones` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |

## Calls

[projectDispatch()](/code/functions/projectDispatch.md), [projectEventPayload()](/code/functions/projectEventPayload.md), [projectMilestoneEventFields()](/code/functions/projectMilestoneEventFields.md), [projectMilestonesReady()](/code/functions/projectMilestonesReady.md), [projectStageDueWindows()](/code/functions/projectStageDueWindows.md)

## Called from

- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertsScan()](/code/functions/projectAlertsScan.md)

