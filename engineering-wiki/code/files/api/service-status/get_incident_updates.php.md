# api/service-status/get_incident_updates.php

> API endpoint - 87 lines

API: the update thread for one incident (discussion #59, phase 2).

GET ?incident_id=21

The rows that make a status page readable: what was said, when, by whom, and
which services were at which impact at that moment. See
includes/services/service_uptime.php for how the same rows are read back as
per-service downtime.

## The endpoint

| | |
|---|---|
| URL | `api/service-status/get_incident_updates.php` |
| Protected by | Module access: service-status (requireModuleAccessJson) |
| Reads | `GET incident_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [status_incident_updates](/code/tables/status_incident_updates.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md), read [analysts](/code/tables/analysts.md) |  |
| 60 |  | read [status_incident_update_services](/code/tables/status_incident_update_services.md), read [status_services](/code/tables/status_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

