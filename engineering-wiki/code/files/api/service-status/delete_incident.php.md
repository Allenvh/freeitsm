# api/service-status/delete_incident.php

> API endpoint - 29 lines

API: Delete an incident. POST - JSON body: { id }

Thin UI adapter over ServiceStatusService.

## The endpoint

| | |
|---|---|
| URL | `api/service-status/delete_incident.php` |
| Protected by | Module access: service-status (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/service_status.php](/code/files/includes/services/service_status.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

