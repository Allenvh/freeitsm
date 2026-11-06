# service_impact_levels

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(50) NOT NULL` |  |
| `colour` | `VARCHAR(20) NULL` |  |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `severity_order` | `INT NOT NULL DEFAULT 99` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `counts_as_downtime` | `TINYINT(1) NOT NULL DEFAULT 1` | Uptime: does time at this level count as downtime? Defaults to 1 so an upgrade is conservative — an existing custom level counts until somebody says otherwise, rather than quietly vanishing from the figures. The catch-up migration below then clears it for Maintenance / Operational / No Disruption, which are the shipped levels where it is plainly wrong. |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3612 |  | insert | `name`, `colour`, `is_default`, `severity_order`, `display_order`, `counts_as_downtime` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3637 |  | update | `counts_as_downtime` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3667 |  | insert | `name`, `severity_order`, `display_order` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):663 | `ServiceStatusService::saveImpactLevel` | update | `is_default` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):669 | `ServiceStatusService::saveImpactLevel` | update | `name`, `colour`, `is_default`, `severity_order`, `display_order`, `is_active`, `counts_as_downtime` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):673 | `ServiceStatusService::saveImpactLevel` | insert | `name`, `colour`, `is_default`, `severity_order`, `display_order`, `is_active`, `counts_as_downtime` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):679 | `ServiceStatusService::saveImpactLevel` | update | `is_default` |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md):710 | `ServiceStatusService::deleteImpactLevel` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md) | L102 |  |
| [api/service-status/get_dashboard.php](/code/files/api/service-status/get_dashboard.php.md) | L34, L76 |  |
| [api/service-status/get_impact_levels.php](/code/files/api/service-status/get_impact_levels.php.md) | L20 |  |
| [api/service-status/get_incident_updates.php](/code/files/api/service-status/get_incident_updates.php.md) | L60 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3610, L3667, L3673, L3680 |  |
| [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) | apiServiceStatusSubquery L54, apiServiceStatusImpactMeta L69, apiSerializeIncident L137, apiServiceImpactLevelsList L380 |  |
| [domains/settings/index.php](/code/files/domains/settings/index.php.md) | L183 |  |
| [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) | domainStatusImpactId L219, domainStatusImpactId L225 |  |
| [includes/feature_bingo/cards/service-status.php](/code/files/includes/feature_bingo/cards/service-status.php.md) | L116 |  |
| [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md) | rpStatusIncidentLog L151, rpStatusIncidentSummary L219 |  |
| [includes/service_impact_levels.php](/code/files/includes/service_impact_levels.php.md) | defaultImpactLevel L37, defaultImpactLevel L44 |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) | statusPlannedList L176 |  |
| [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md) | ssPortalIncidents L139 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::announcements L2077 |  |
| [includes/services/service_status.php](/code/files/includes/services/service_status.php.md) | ServiceStatusService::validateIncidentServices L345, ServiceStatusService::validateIncidentServices L354, ServiceStatusService::saveImpactLevel L677, ServiceStatusService::deleteImpactLevel L699, ServiceStatusService::deleteImpactLevel L709 |  |
| [includes/services/service_uptime.php](/code/files/includes/services/service_uptime.php.md) | ServiceUptime::incidentsFor L112, ServiceUptime::segmentsFor L216, ServiceUptime::dailyStrip L389, ServiceUptime::incidentsBetween L463, ServiceUptime::dailyStripBetween L569 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L435 |  |

