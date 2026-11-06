# api/service-status/get_dashboard.php

> API endpoint - 102 lines

API: Get dashboard data for Service Status module GET - Returns all active services with worst current impact + recent incidents

## The endpoint

| | |
|---|---|
| URL | `api/service-status/get_dashboard.php` |
| Protected by | Module access: service-status (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | read [status_services](/code/tables/status_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md), read [status_incident_services](/code/tables/status_incident_services.md), read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 57 |  | read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md), read [analysts](/code/tables/analysts.md) |  |
| 76 |  | read [status_incident_services](/code/tables/status_incident_services.md), read [status_services](/code/tables/status_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/service_impact_levels.php](/code/files/includes/service_impact_levels.php.md), [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [defaultImpactLevel()](/code/functions/defaultImpactLevel.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [statusPlannedDue()](/code/functions/statusPlannedDue.md), [statusPlannedList()](/code/functions/statusPlannedList.md)

