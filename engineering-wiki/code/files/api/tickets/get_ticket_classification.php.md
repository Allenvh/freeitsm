# api/tickets/get_ticket_classification.php

> API endpoint - 168 lines

API Endpoint: everything the ticket classification feature needs, in one call (#1540) — the category tree, the resolution codes, and the three switches deciding whether any of it appears on a ticket.

One endpoint rather than three because every consumer wants the same bundle:
a ticket form needs the categories AND whether to render them at all, and
fetching those separately means a page can briefly render a field the settings
say to hide.

Query parameters:
  manage=1   the settings screen: the WHOLE list including retired categories,
             plus each company's switch overrides. Requires the settings cap.
  type_id=N  only categories on offer for that ticket type (roots with no type
             at all, plus that type's roots, plus everything beneath either).
  portal=1   only categories a requester may see.

Without manage=1 this is a CONSUMER view: active categories only, resolved for
the company whose context the analyst is working in.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_classification.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_CATEGORIES (requireCapabilityJson) |
| Reads | `GET manage`, `GET portal`, `GET type_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 64 |  | read [tickets](/code/tables/tickets.md) |  |
| 67 |  | read [tickets](/code/tables/tickets.md) |  |
| 73 |  | read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getAllTenants()](/code/functions/getAllTenants.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md), [isActiveTenantAll()](/code/functions/isActiveTenantAll.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [tenantSettingOn()](/code/functions/tenantSettingOn.md), [tenantSettingsForKey()](/code/functions/tenantSettingsForKey.md), [ticketCategoriesResolved()](/code/functions/ticketCategoriesResolved.md), [ticketClassificationSettings()](/code/functions/ticketClassificationSettings.md), [ticketResolutionCodesResolved()](/code/functions/ticketResolutionCodesResolved.md)

