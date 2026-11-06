# api/self-service/get_dashboard.php

> API endpoint - 173 lines

API: Self-Service Dashboard Data GET - Returns ticket summary, recent tickets, and service status for the logged-in user

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_dashboard.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 40 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 80 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [departments](/code/tables/departments.md) |  |
| 102 |  | read [status_services](/code/tables/status_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md), read [status_incident_services](/code/tables/status_incident_services.md), read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 131 |  | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md), read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/service_impact_levels.php](/code/files/includes/service_impact_levels.php.md), [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md), [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [defaultImpactLevel()](/code/functions/defaultImpactLevel.md), [ssPortalIncidents()](/code/functions/ssPortalIncidents.md), [ssPortalUpdatesEnabled()](/code/functions/ssPortalUpdatesEnabled.md), [statusPlannedDue()](/code/functions/statusPlannedDue.md), [statusPlannedList()](/code/functions/statusPlannedList.md)

