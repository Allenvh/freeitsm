# projectAlertsRun()

The whole scan as nobody - what cron and the portfolio fallback call.

```php
function projectAlertsRun(PDO $conn): array
```

Defined in [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), line 278.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[projectAlertsAsSystem()](/code/functions/projectAlertsAsSystem.md), [projectAlertsScan()](/code/functions/projectAlertsScan.md)

## Called from

- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertsOpportunistic()](/code/functions/projectAlertsOpportunistic.md)
- [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md) - in [workflowScheduledRun()](/code/functions/workflowScheduledRun.md)

