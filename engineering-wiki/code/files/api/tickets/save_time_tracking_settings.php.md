# api/tickets/save_time_tracking_settings.php

> API endpoint - 87 lines

API Endpoint: save the time-tracking switches (discussion #72).

POST {
  default:   { ui: bool, api: bool },
  companies: [ { id: N, ui: bool|null, api: bool|null } ]
}

⚠️ `null` for a company means "follow the install default" and DELETES its
override — it is not the same as false. Without that a company could be given
an answer and never handed back to the default again.

⚠️ NOTHING IS DELETED BY TURNING THESE OFF. dschipfel asked for existing data
to be preserved and it is: time entries stay exactly where they are and come
back untouched when it is switched on again. These switches decide what is
SHOWN and what is SERVED, never what is kept.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_time_tracking_settings.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MANAGE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [setTenantSetting()](/code/functions/setTenantSetting.md)

