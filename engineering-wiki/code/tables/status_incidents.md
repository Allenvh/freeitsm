# status_incidents

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `status_id` | `INT NULL` |  |
| `comment` | `LONGTEXT NULL` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `resolved_datetime` | `DATETIME NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3655 |  | update | `status_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3662 |  | update | `status_id` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):141 | `ServiceStatusService::saveIncident` | insert | `title`, `status_id`, `comment`, `created_by_id`, `created_datetime`, `updated_datetime`, `resolved_datetime` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):207 | `ServiceStatusService::updateIncident` | update | `title`, `status_id`, `comment`, `resolved_datetime`, `updated_datetime` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):213 | `ServiceStatusService::updateIncident` | update | `title`, `status_id`, `comment`, `resolved_datetime`, `updated_datetime` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):272 | `ServiceStatusService::deleteIncident` | delete |  |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):437 | `ServiceStatusService::editIncidentUpdate` | update | `comment` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):469 | `ServiceStatusService::deleteIncidentUpdate` | update | `comment` |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):309 | `statusPlannedStart` | update | `created_datetime` |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md):340 | `statusPlannedFinish` | update | `resolved_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md) | L102 |  |
| [api/service-status/get_dashboard.php](/code/files/api/service-status/get_dashboard.php.md) | L34, L57 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3649 |  |
| [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) | apiServiceStatusSubquery L54, apiServiceIncidentSelect L122, apiStatusIncidentsList L305 |  |
| [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) | domainStatusOpenIncident L120, domainStatusRun L280 |  |
| [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md) | rpStatusIncidentLog L133, rpStatusIncidentSummary L204, rpStatusKpis L270 |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) | statusPlannedFinish L327 |  |
| [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md) | ssPortalIncidents L118 |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md) | ServiceStatusService::loadIncidentRow L290, ServiceStatusService::deleteIncidentStatus L616 |  |
| [includes/services/service_uptime.php](/code/files/includes/services/service_uptime.php.md) | ServiceUptime::incidentsFor L112, ServiceUptime::incidentsBetween L463 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolServiceStatus L456 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L435, getWatchtowerData L468 |  |

