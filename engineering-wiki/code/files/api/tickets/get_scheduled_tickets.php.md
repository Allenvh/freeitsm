# api/tickets/get_scheduled_tickets.php

> API endpoint - 133 lines

API Endpoint: Get scheduled tickets for calendar view

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_scheduled_tickets.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET start`, `GET end`, `GET year`, `GET month`, `GET scope` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [departments](/code/tables/departments.md), read [analysts](/code/tables/analysts.md), read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [scheduleSchemaReady()](/code/functions/scheduleSchemaReady.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)

