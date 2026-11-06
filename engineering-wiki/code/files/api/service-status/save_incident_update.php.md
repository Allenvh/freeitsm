# api/service-status/save_incident_update.php

> API endpoint - 46 lines

API: correct an update that has already been posted (Ed, on top of #99).

POST { id, comment?, is_internal? }

⚠️ Editing IN PLACE rather than posting again. Fixing a typo used to mean
saving the incident, which appended a second entry — so a customer read the
same sentence twice with the wrong version first.

Only the wording and who can see it may change. The status and the service
impacts on an update are what was true at that moment; rewriting them would
turn the timeline into a record of what somebody wishes had happened.

## The endpoint

| | |
|---|---|
| URL | `api/service-status/save_incident_update.php` |
| Protected by | Module access: service-status (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/service_status.php](/code/files/includes/services/service_status.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

