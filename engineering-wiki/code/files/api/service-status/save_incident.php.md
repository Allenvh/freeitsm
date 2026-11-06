# api/service-status/save_incident.php

> API endpoint - 29 lines

API: Save (create or update) an incident. POST - JSON body: { id?, title, status, comment, services: [{ service_id, impact_level }] }

Thin UI adapter over ServiceStatusService (shared with the REST API).

## The endpoint

| | |
|---|---|
| URL | `api/service-status/save_incident.php` |
| Protected by | Module access: service-status (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/service_status.php](/code/files/includes/services/service_status.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

