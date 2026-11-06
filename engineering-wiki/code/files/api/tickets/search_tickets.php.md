# api/tickets/search_tickets.php

> API endpoint - 114 lines

API Endpoint: Search tickets Searches tickets by ticket number, email address, or subject

## The endpoint

| | |
|---|---|
| URL | `api/tickets/search_tickets.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 67 |  | read [tickets](/code/tables/tickets.md), read [emails](/code/tables/emails.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 89 |  | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)

