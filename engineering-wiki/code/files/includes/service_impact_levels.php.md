# includes/service_impact_levels.php

> PHP - 62 lines

The baseline impact level — "nothing is wrong with this service right now".

A service with no open incident has no row in status_incident_services, so its
status is not stored anywhere; it is derived. That derived value used to be the
hardcoded string 'Operational' in six separate places (GH #70). Rename the level
on Service status → Settings → Impact levels and every one of them kept saying
"Operational" — a name that no longer matched any row, so the badge also lost
its colour, while a service that WAS in an incident showed the new name
correctly. Two services, two different names for the same level.

service_impact_levels.is_default is the flag that already means "baseline":
saving one clears the rest, deleting it is refused, and if the table somehow
ends up with none, the least severe level is promoted. So resolve the fallback
from that row rather than from a literal, and hand back its colour too — the
callers need the badge colour for a level that is renamed OR deactivated.

No dependencies beyond PDO, deliberately: the self-service portal dashboard
needs this and should not have to pull in the workflow engine to get it.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [defaultImpactLevel()](/code/functions/defaultImpactLevel.md) | 29 | The impact level a service shows when nothing is wrong with it. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 | `defaultImpactLevel` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 44 | `defaultImpactLevel` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## How it hangs together

**Included by:** [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md), [api/service-status/get_dashboard.php](/code/files/api/service-status/get_dashboard.php.md), [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), [includes/services/service_status.php](/code/files/includes/services/service_status.php.md), [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md)

