# status_services

Service Status module

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(500) NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):49 | `ServiceStatusService::saveService` | update | `name`, `description`, `display_order`, `is_active` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):61 | `ServiceStatusService::saveService` | insert | `name`, `description`, `display_order`, `is_active`, `created_datetime` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):85 | `ServiceStatusService::deleteService` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md) | L102 |  |
| [api/service-status/get_dashboard.php](/code/files/api/service-status/get_dashboard.php.md) | L34, L76 |  |
| [api/service-status/get_incident_updates.php](/code/files/api/service-status/get_incident_updates.php.md) | L60 |  |
| [api/service-status/get_service_history.php](/code/files/api/service-status/get_service_history.php.md) | L41 |  |
| [api/service-status/get_services.php](/code/files/api/service-status/get_services.php.md) | L21 |  |
| [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) | apiLoadService L106, apiSerializeIncident L137, apiStatusServicesList L213 |  |
| [includes/domains/links.php](/code/files/includes/domains/links.php.md) | domainLinkTargetOk L125, domainLinkDescribe L167, domainLinkSearch L284 |  |
| [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) | domainStatusServiceIds L106 |  |
| [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md) | rpOptionSource L422 |  |
| [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md) | rpStatusServices L25, rpStatusIncidentLog L151, rpStatusIncidentSummary L219 |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) | statusPlannedList L176 |  |
| [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md) | ssPortalIncidents L139 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::announcements L2076 |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md) | ServiceStatusService::loadServiceRow L101, ServiceStatusService::validateIncidentServices L339 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolServiceStatus L449, warbotToolServiceStatus L456 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L435, getWatchtowerData L644 |  |

