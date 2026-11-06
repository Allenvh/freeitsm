# api/service-status/delete_incident_status.php

> API endpoint - 29 lines

API Endpoint: Delete service incident status. Thin UI adapter over ServiceStatusService.

## The endpoint

| | |
|---|---|
| URL | `api/service-status/delete_incident_status.php` |
| Protected by | Module access: service-status (requireModuleAccessJson); Permission: Cap::SERVICE_STATUS_STATUSES (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/service_status.php](/code/files/includes/services/service_status.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

