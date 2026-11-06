# api/tickets/save_ticket_classification_settings.php

> API endpoint - 93 lines

API Endpoint: save the three "does this field appear on a ticket" switches (#1540).

POST {
  default:   { category: bool, closure_category: bool, resolution_code: bool },
  companies: [ { id: N, category: bool|null, closure_category: bool|null, resolution_code: bool|null } ]
}

⚠️ `null` for a company means "follow the install default" and DELETES its
override — it is not the same as false. Without that distinction a company
could be given an answer and never handed back to the default again.

⚠️ NOTHING IS DELETED BY TURNING THESE OFF. A ticket already carrying a
category keeps it, and it reappears untouched when the switch goes back on.
These decide what is SHOWN, never what is stored.

🔑 The three are INDEPENDENT and nothing here couples them. "Category off,
category at close on" is a real service desk — don't make the person raising
the ticket guess, let the analyst classify once they actually know.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_ticket_classification_settings.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_CATEGORIES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [setTenantSetting()](/code/functions/setTenantSetting.md)

