# api/service-status/planned.php

> API endpoint - 45 lines

API: Planned maintenance (3.2.0) - see includes/service_status_planned.php.

GET  ?states=scheduled,started,finished,cancelled   (default: scheduled + started)
POST {action:'save', id?, title, comment?, start (ISO UTC), end?, services:[{service_id, impact_level_id}]}
POST {action:'cancel', id}

Thin UI adapter; the rules live in the include. Needs Service Status.

## The endpoint

| | |
|---|---|
| URL | `api/service-status/planned.php` |
| Protected by | Module access: service-status (requireModuleAccessJson) |
| Reads | `GET states` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [statusPlannedCancel()](/code/functions/statusPlannedCancel.md), [statusPlannedDue()](/code/functions/statusPlannedDue.md), [statusPlannedList()](/code/functions/statusPlannedList.md), [statusPlannedSave()](/code/functions/statusPlannedSave.md)

