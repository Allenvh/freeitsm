# api/service-status/save_impact_level.php

> API endpoint - 34 lines

API Endpoint: Save service impact level (create or update).

severity_order drives "worst current impact" ordering on dashboards
(1 = worst e.g. Major Outage, higher = less severe). Two rows can share the
same severity_order; ties break on the lookup id.

Thin UI adapter over ServiceStatusService.

## The endpoint

| | |
|---|---|
| URL | `api/service-status/save_impact_level.php` |
| Protected by | Module access: service-status (requireModuleAccessJson); Permission: Cap::SERVICE_STATUS_IMPACTS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/service_status.php](/code/files/includes/services/service_status.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

