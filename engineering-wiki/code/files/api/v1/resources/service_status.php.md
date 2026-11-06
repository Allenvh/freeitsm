# api/v1/resources/service_status.php

> API endpoint - 394 lines

FreeITSM REST API v1 — service-status resource (services + incidents).

Mirrors the module's internal endpoints:
  - a service's state is DERIVED, not stored: the worst impact level
    (lowest severity_order) across its open incidents, else the configured
    default impact level (usually Operational, but it is renameable — GH #70)
    — the services list computes it inline with get_dashboard.php's exact
    subquery, so status-page builders get the health board in one call;
  - incident writes mirror save_incident.php: status by name (or id),
    resolved_datetime stamped once on entering a resolved status
    (COALESCE-preserved) and cleared on reopen, affected services replaced
    as a set with per-service impact levels;
  - deletes replicate the UI's manual junction cleanup — but inside a
    transaction (the internal endpoints run two bare statements).

One deliberate improvement, documented: unknown service/impact ids in the
affected-services array are a 422 (save_incident.php silently skips them —
machines deserve the error).

This is the module monitoring tools want to DRIVE: flip an incident open
when a probe fails, append status, resolve on recovery. Everything here is
key-authenticated like the rest of v1 — there is no public unauthenticated
status feed in the product (yet). Install-wide; no audit trail exists.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/service_status.php` |
| Protected by | REST API key |
| Reads | `GET is_active`, `GET q`, `GET state`, `GET service_id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiServiceStatusFail()](/code/functions/apiServiceStatusFail.md) | 37 | Map a ServiceError raised by the shared service to the API's error response. |
| [apiServiceStatusSubquery()](/code/functions/apiServiceStatusSubquery.md) | 52 | get_dashboard.php's exact worst-open-impact subquery, as a SQL fragment. |
| [apiServiceStatusImpactMeta()](/code/functions/apiServiceStatusImpactMeta.md) | 65 | Every impact level's colour and severity order, keyed by name - read once per request and cached. |
| [apiSerializeService()](/code/functions/apiSerializeService.md) | 82 | Shapes a service row for the API, with its current status (the default impact level when nothing is affecting it) and that status's colour and severity. |
| [apiLoadService()](/code/functions/apiLoadService.md) | 104 | Loads one service row with its current status, or answers 404 and stops. |
| [apiServiceIncidentSelect()](/code/functions/apiServiceIncidentSelect.md) | 121 | The SELECT for service incidents, with the status's name, colour and resolved flag and the creator's name. |
| [apiSerializeIncident()](/code/functions/apiSerializeIncident.md) | 135 | Shapes an incident row for the API, with every service it affects and the impact on each, worst first. |
| [apiLoadIncident()](/code/functions/apiLoadIncident.md) | 179 | Loads one incident row (apiServiceIncidentSelect), or answers 404 and stops. |
| [apiStatusServicesList()](/code/functions/apiStatusServicesList.md) | 197 | GET /service-status/services — the health board (derived current_status inline) |
| [apiStatusServicesGet()](/code/functions/apiStatusServicesGet.md) | 227 | GET /service-status/services/{id} - one service with its open (unresolved) incidents, most recently updated first. |
| [apiStatusServicesCreate()](/code/functions/apiStatusServicesCreate.md) | 247 | POST /service-status/services - creates a service through ServiceStatusService and returns it. |
| [apiStatusServicesUpdate()](/code/functions/apiStatusServicesUpdate.md) | 258 | PATCH /service-status/services/{id} - updates a service through ServiceStatusService and returns it. |
| [apiStatusServicesDelete()](/code/functions/apiStatusServicesDelete.md) | 266 | DELETE /service-status/services/{id} - deletes a service through ServiceStatusService. |
| [apiStatusIncidentsList()](/code/functions/apiStatusIncidentsList.md) | 278 | GET /service-status/incidents |
| [apiStatusIncidentsGet()](/code/functions/apiStatusIncidentsGet.md) | 329 | GET /service-status/incidents/{id} - one incident with the services it affects. |
| [apiStatusIncidentsCreate()](/code/functions/apiStatusIncidentsCreate.md) | 334 | POST /service-status/incidents |
| [apiStatusIncidentsUpdate()](/code/functions/apiStatusIncidentsUpdate.md) | 342 | PATCH /service-status/incidents/{id} |
| [apiStatusIncidentsDelete()](/code/functions/apiStatusIncidentsDelete.md) | 350 | DELETE /service-status/incidents/{id} - deletes an incident through ServiceStatusService. |
| [apiServiceIncidentStatusesList()](/code/functions/apiServiceIncidentStatusesList.md) | 360 | Reference lookups |
| [apiServiceImpactLevelsList()](/code/functions/apiServiceImpactLevelsList.md) | 378 | GET /service-impact-levels - the impact levels, in severity order. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 54 | `apiServiceStatusSubquery` | read [status_incident_services](/code/tables/status_incident_services.md), read [status_incidents](/code/tables/status_incidents.md), read [service_impact_levels](/code/tables/service_impact_levels.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 69 | `apiServiceStatusImpactMeta` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 106 | `apiLoadService` | read [status_services](/code/tables/status_services.md) |  |
| 122 | `apiServiceIncidentSelect` | read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md), read [analysts](/code/tables/analysts.md) |  |
| 137 | `apiSerializeIncident` | read [status_incident_services](/code/tables/status_incident_services.md), read [status_services](/code/tables/status_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 213 | `apiStatusServicesList` | read [status_services](/code/tables/status_services.md) |  |
| 232 | `apiStatusServicesGet` | read [status_incident_services](/code/tables/status_incident_services.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 287 | `apiStatusIncidentsList` | read [status_incident_services](/code/tables/status_incident_services.md) |  |
| 305 | `apiStatusIncidentsList` | read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 362 | `apiServiceIncidentStatusesList` | read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 380 | `apiServiceImpactLevelsList` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/service_status.php](/code/files/includes/services/service_status.php.md), [includes/service_impact_levels.php](/code/files/includes/service_impact_levels.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseDate()](/code/functions/apiParseDate.md), [apiRespond()](/code/functions/apiRespond.md), [defaultImpactLevel()](/code/functions/defaultImpactLevel.md), [serviceErrorHttpStatus()](/code/functions/serviceErrorHttpStatus.md)

