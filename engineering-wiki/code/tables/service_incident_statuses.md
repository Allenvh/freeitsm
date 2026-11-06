# service_incident_statuses

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(50) NOT NULL` |  |
| `is_resolved` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `colour` | `VARCHAR(20) NULL` |  |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3599 |  | insert | `name`, `is_resolved`, `colour`, `is_default`, `display_order` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3649 |  | insert | `name`, `display_order` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):576 | `ServiceStatusService::saveIncidentStatus` | update | `is_default` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):582 | `ServiceStatusService::saveIncidentStatus` | update | `name`, `colour`, `is_resolved`, `is_default`, `display_order`, `is_active` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):586 | `ServiceStatusService::saveIncidentStatus` | insert | `name`, `colour`, `is_resolved`, `is_default`, `display_order`, `is_active` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):592 | `ServiceStatusService::saveIncidentStatus` | update | `is_default` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):623 | `ServiceStatusService::deleteIncidentStatus` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md) | L102 |  |
| [api/service-status/get_dashboard.php](/code/files/api/service-status/get_dashboard.php.md) | L34, L57 |  |
| [api/service-status/get_incident_statuses.php](/code/files/api/service-status/get_incident_statuses.php.md) | L20 |  |
| [api/service-status/get_incident_updates.php](/code/files/api/service-status/get_incident_updates.php.md) | L42 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3597, L3649, L3655, L3662 |  |
| [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) | apiServiceStatusSubquery L54, apiServiceIncidentSelect L122, apiStatusServicesGet L232, apiStatusIncidentsList L305, apiServiceIncidentStatusesList L362 |  |
| [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) | domainStatusResolvedName L235 |  |
| [includes/feature_bingo/cards/service-status.php](/code/files/includes/feature_bingo/cards/service-status.php.md) | L131 |  |
| [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md) | rpStatusIncidentLog L133, rpStatusIncidentSummary L204 |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) | statusPlannedStatusId L281, statusPlannedStatusId L282, statusPlannedFinish L327, statusPlannedFinish L332 |  |
| [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md) | ssPortalIncidents L118, ssPortalIncidentUpdates L201 |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md) | ServiceStatusService::loadIncidentRow L290, ServiceStatusService::resolveIncidentStatus L309, ServiceStatusService::resolveIncidentStatus L312, ServiceStatusService::saveIncidentStatus L590, ServiceStatusService::deleteIncidentStatus L612, ServiceStatusService::deleteIncidentStatus L622 |  |
| [includes/services/service_uptime.php](/code/files/includes/services/service_uptime.php.md) | ServiceUptime::incidentsFor L112 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolServiceStatus L456 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L435, getWatchtowerData L468 |  |

