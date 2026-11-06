# projectAlertsBenefits()

Reviews that have fallen due -> project.benefit_review_due, once per benefit per review date. Every project that is not cancelled: benefits outlive it. Called from `projectAlertsScan()`; quiet before Database Verification.

```php
function projectAlertsBenefits(PDO $conn, ?int $projectId = null): int
```

Defined in [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md), line 162.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `?int` | `null` | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 166 | `projectAlertsBenefits` | read [project_benefits](/code/tables/project_benefits.md), read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 179 | `projectAlertsBenefits` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |

## Calls

[projectAlertsAsSystem()](/code/functions/projectAlertsAsSystem.md), [projectBenefitNotifyIds()](/code/functions/projectBenefitNotifyIds.md), [projectBenefitsReady()](/code/functions/projectBenefitsReady.md), [projectDispatch()](/code/functions/projectDispatch.md), [projectEventPayload()](/code/functions/projectEventPayload.md)

## Called from

- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertsScan()](/code/functions/projectAlertsScan.md)

## Traps

> [!WARNING]
> **Line 188, in `projectAlertsBenefits`:** a review falling due is nobody's action. This scan also runs inside somebody's request (afterChange), and the bell never tells you about your own action - so it is dispatched as the system, or the project manager who happened to close the project would never hear that a review is due.

