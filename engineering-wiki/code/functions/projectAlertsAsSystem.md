# projectAlertsAsSystem()

Run `$fn` with nobody signed in, as cron does - see the TRAP above.

```php
function projectAlertsAsSystem(callable $fn)
```

Defined in [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), line 107.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fn` | `callable` |  | the work to run with the session's analyst hidden; its return value is passed back |

## Called from

- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertsRun()](/code/functions/projectAlertsRun.md)
- [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md) - in [projectAlertsBenefits()](/code/functions/projectAlertsBenefits.md)
- [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) - in [projectAlertsOverdueDigest()](/code/functions/projectAlertsOverdueDigest.md), [projectAlertsStalled()](/code/functions/projectAlertsStalled.md)
- [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md) - in `ProjectReportsService::runSchedules`

