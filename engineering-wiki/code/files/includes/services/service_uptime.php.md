# includes/services/service_uptime.php

> PHP - 659 lines

Service history and uptime, DERIVED from incidents (discussion #59).

── Why there is nothing to "log" ────────────────────────────────────────────

The request was for a table recording every status change per service. There
is no such thing to record: `status_services` has no status column. A
service's status is computed live in get_dashboard.php as

    the worst impact level among its OPEN incidents, else 'Operational'

so a history table of previous → new would sit permanently empty.

What already exists is better. Every period a service was not Operational IS
an incident that touched it:

    start   status_incidents.created_datetime
    end     status_incidents.resolved_datetime  (or now, if still open)
    what    status_incident_services.impact_level_id

So the history can be derived from data every install already has, which
means it works RETROSPECTIVELY on outages that have already happened rather
than shipping empty and becoming useful in six months.

── What this deliberately cannot see yet ────────────────────────────────────

⚠️ Changes DURING an incident. `replaceIncidentServices()` deletes and
re-inserts the service links, so moving a service from Major Outage to
Degraded mid-incident overwrites the earlier value — this will report the
whole incident at whatever the level ended up as. Incident status transitions
(Investigating → Identified → Monitoring) are not timestamped either.

That is the phase-two work: one incident-updates log, written where incidents
are saved, which this function would then read in preference to the
incident's own start/end. Until then every figure here is "per incident"
rather than "per change", and the UI says so rather than implying a precision
it does not have.

## Classes

- [ServiceUptime](/code/classes/ServiceUptime.md) (class, line 43)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 57 | `ServiceUptime::defaultWindowDays` | read [system_settings](/code/tables/system_settings.md) |  |
| 73 | `ServiceUptime::shownInPortal` | read [system_settings](/code/tables/system_settings.md) |  |
| 107 | `ServiceUptime::incidentsFor` | read [status_incident_update_services](/code/tables/status_incident_update_services.md), read [status_incident_updates](/code/tables/status_incident_updates.md) |  |
| 112 | `ServiceUptime::incidentsFor` | read [status_incidents](/code/tables/status_incidents.md), read [status_incident_services](/code/tables/status_incident_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 184 | `ServiceUptime::updateLogAvailable` | read [status_incident_update_services](/code/tables/status_incident_update_services.md) |  |
| 216 | `ServiceUptime::segmentsFor` | read [status_incident_updates](/code/tables/status_incident_updates.md), read [status_incident_update_services](/code/tables/status_incident_update_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 389 | `ServiceUptime::dailyStrip` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 458 | `ServiceUptime::incidentsBetween` | read [status_incident_update_services](/code/tables/status_incident_update_services.md), read [status_incident_updates](/code/tables/status_incident_updates.md) |  |
| 463 | `ServiceUptime::incidentsBetween` | read [status_incidents](/code/tables/status_incidents.md), read [status_incident_services](/code/tables/status_incident_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 569 | `ServiceUptime::dailyStripBetween` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [api/service-status/get_service_history.php](/code/files/api/service-status/get_service_history.php.md), [api/service-status/save_uptime_settings.php](/code/files/api/service-status/save_uptime_settings.php.md), [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md), [service-status/settings/index.php](/code/files/service-status/settings/index.php.md)

