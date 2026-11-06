# includes/service_status_portal.php

> PHP - 217 lines

What the self-service portal shows about service status (discussion #99).

The requester asked for internal and external comments on incidents, with the
external ones visible to end users. Worth being clear about what changed:
before this, updates were not "all treated the same" so much as ALL INTERNAL,
because the portal never showed any of them. It showed a service and a
colour. So this is mostly about starting to communicate, not about splitting
something that was already public.

⚠️ EVERY portal-facing read goes through here. There is exactly one query
that decides what an end user may see, and it is the one below.

🔴 OFF BY DEFAULT, and that is not timidity. Turning it on publishes incident
TITLES, which were written when nobody expected a customer to read them — an
upgrade that started showing "Exchange DAG failover borked again" on the
portal would be doing something no upgrade should be able to do. An
administrator turns it on under System once they have looked.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ssPortalSettings()](/code/functions/ssPortalSettings.md) | 43 | All three settings, with defaults filled in for anything unset. |
| [ssPortalSetting()](/code/functions/ssPortalSetting.md) | 68 | One setting, falling back to its default. |
| [ssPortalUpdatesEnabled()](/code/functions/ssPortalUpdatesEnabled.md) | 74 | Are incidents shown on the portal at all? |
| [ssPortalIncidents()](/code/functions/ssPortalIncidents.md) | 87 | Incidents the portal may show, newest first, each with its EXTERNAL updates. |
| [ssPortalIncidentUpdates()](/code/functions/ssPortalIncidentUpdates.md) | 194 | The EXTERNAL updates on one incident, oldest first — the progression from "we are looking at it" to "it is fixed", which is what a status page is for. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 | `ssPortalSettings` | read [system_settings](/code/tables/system_settings.md) |  |
| 101 | `ssPortalIncidents` | read [status_incident_updates](/code/tables/status_incident_updates.md) |  |
| 118 | `ssPortalIncidents` | read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 139 | `ssPortalIncidents` | read [status_incident_services](/code/tables/status_incident_services.md), read [status_services](/code/tables/status_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 166 | `ssPortalIncidents` | read [status_incident_updates](/code/tables/status_incident_updates.md) |  |
| 201 | `ssPortalIncidentUpdates` | read [status_incident_updates](/code/tables/status_incident_updates.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |

## How it hangs together

**Included by:** [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md), [api/self-service/get_incident_updates.php](/code/files/api/self-service/get_incident_updates.php.md), [api/system/service_status_portal.php](/code/files/api/system/service_status_portal.php.md), [service-status/index.php](/code/files/service-status/index.php.md)

