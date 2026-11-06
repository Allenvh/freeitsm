# api/tickets/save_time_entry.php

> API endpoint - 53 lines

API Endpoint: Create or update a ticket time entry. Thin UI adapter over TicketsService (createTimeEntry / updateTimeEntry). On update, only the entry's own analyst may edit it.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_time_entry.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketTenantId()](/code/functions/ticketTenantId.md), [timeTrackingUiOn()](/code/functions/timeTrackingUiOn.md)

