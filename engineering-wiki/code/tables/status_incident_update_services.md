# status_incident_update_services

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `update_id` | `INT NOT NULL` |  |
| `service_id` | `INT NOT NULL` |  |
| `impact_level_id` | `INT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):538 | `ServiceStatusService::recordIncidentUpdate` | insert | `update_id`, `service_id`, `impact_level_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/service-status/get_incident_updates.php](/code/files/api/service-status/get_incident_updates.php.md) | L60 |  |
| [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md) | rpStatusIncidentLog L151 |  |
| [includes/services/service_uptime.php](/code/files/includes/services/service_uptime.php.md) | ServiceUptime::incidentsFor L107, ServiceUptime::updateLogAvailable L184, ServiceUptime::segmentsFor L216, ServiceUptime::incidentsBetween L458 |  |

