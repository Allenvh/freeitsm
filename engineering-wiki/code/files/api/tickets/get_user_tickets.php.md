# api/tickets/get_user_tickets.php

> API endpoint - 73 lines

API Endpoint: Get tickets for a specific user Returns all tickets associated with a user

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_user_tickets.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET user_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [departments](/code/tables/departments.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)

