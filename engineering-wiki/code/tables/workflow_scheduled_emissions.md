# workflow_scheduled_emissions

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `trigger_event` | `VARCHAR(100) NOT NULL` |  |
| `entity_key` | `VARCHAR(120) NOT NULL` |  |
| `fingerprint` | `VARCHAR(64) NOT NULL` |  |
| `emitted_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md):253 | `projectAlertsStagesDue` | insert | `trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime` |  |
| [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md):349 | `projectAlertsMilestones` | insert | `trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime` |  |
| [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md):179 | `projectAlertsBenefits` | insert | `trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime` |  |
| [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md):54 | `projectAlertsOverdueDigest` | insert | `trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime` |  |
| [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md):139 | `projectAlertsStalled` | insert | `trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime` |  |
| [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md):395 | `ProjectReportsService::runSchedules` | insert | `trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime` |  |
| [includes/task_contractors.php](/code/files/includes/task_contractors.php.md):213 | `tasksContractorReminders` | insert | `trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime` |  |
| [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md):79 | `workflowEmitOnce` | insert | `trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime` |  |
| [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md):278 | `workflowPruneEmissions` | delete |  |  |

