# api/service-status/delete_incident_update.php

> API endpoint - 41 lines

API: remove one update from an incident's timeline (Ed, on top of #99). POST { id }

The incident and every other update survive it. Worth having alongside
editing: a correction fixes wording, but an update posted to the wrong
incident, or published by mistake, needs to go rather than be reworded.

## The endpoint

| | |
|---|---|
| URL | `api/service-status/delete_incident_update.php` |
| Protected by | Module access: service-status (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/service_status.php](/code/files/includes/services/service_status.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

