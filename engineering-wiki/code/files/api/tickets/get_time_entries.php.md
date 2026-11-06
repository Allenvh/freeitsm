# api/tickets/get_time_entries.php

> API endpoint - 86 lines

API Endpoint: List time entries for a ticket

Returns only active (is_active = 1) entries, newest entry_datetime first.
Each row carries the logging analyst's name for inline display.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_time_entries.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 47 |  | read [ticket_time_entries](/code/tables/ticket_time_entries.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketTenantId()](/code/functions/ticketTenantId.md), [timeTrackingUiOn()](/code/functions/timeTrackingUiOn.md)

