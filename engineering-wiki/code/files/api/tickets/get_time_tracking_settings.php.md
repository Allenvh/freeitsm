# api/tickets/get_time_tracking_settings.php

> API endpoint - 65 lines

API Endpoint: time-tracking settings — the install default and every company's override (discussion #72).

Returns the two switches:
  ui   — should the time-recording panel appear on a ticket
  api  — should the REST API serve time entries for that ticket

They are separate on purpose. Hiding the panel is about interface clutter;
silently emptying an API endpoint breaks integrations belonging to people who
changed nothing. Different decisions, different switches.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_time_tracking_settings.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MANAGE (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getAllTenants()](/code/functions/getAllTenants.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [tenantSettingOn()](/code/functions/tenantSettingOn.md), [tenantSettingsForKey()](/code/functions/tenantSettingsForKey.md)

