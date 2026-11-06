# status_incident_updates

Incident update log (discussion #59, phase 2). Each row is a moment; the per-service impacts at that moment live in the table below. See includes/services/service_uptime.php for how the two are read back.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `incident_id` | `INT NOT NULL` |  |
| `status_id` | `INT NULL` |  |
| `comment` | `LONGTEXT NULL` |  |
| `is_internal` | `TINYINT(1) NOT NULL DEFAULT 1` | DEFAULT 1 (#99): rows written before this column existed become internal, so Database Verification cannot publish old troubleshooting notes to the portal. |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):428 | `ServiceStatusService::editIncidentUpdate` | update | `comment`, `is_internal` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):461 | `ServiceStatusService::deleteIncidentUpdate` | delete |  |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):532 | `ServiceStatusService::recordIncidentUpdate` | insert | `incident_id`, `status_id`, `comment`, `is_internal`, `created_by_id`, `created_datetime` |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):310 | `statusPlannedStart` | update | `created_datetime` |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):342 | `statusPlannedFinish` | update | `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/service-status/get_incident_updates.php](/code/files/api/service-status/get_incident_updates.php.md) | L42 |  |
| [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md) | rpStatusIncidentLog L133 |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) | statusPlannedFinish L342 |  |
| [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md) | ssPortalIncidents L101, ssPortalIncidents L166, ssPortalIncidentUpdates L201 |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md) | ServiceStatusService::editIncidentUpdate L414, ServiceStatusService::editIncidentUpdate L434, ServiceStatusService::deleteIncidentUpdate L453, ServiceStatusService::deleteIncidentUpdate L464 |  |
| [includes/services/service_uptime.php](/code/files/includes/services/service_uptime.php.md) | ServiceUptime::incidentsFor L107, ServiceUptime::segmentsFor L216, ServiceUptime::incidentsBetween L458 |  |

