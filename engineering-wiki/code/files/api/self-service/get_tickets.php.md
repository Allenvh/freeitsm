# api/self-service/get_tickets.php

> API endpoint - 62 lines

API: Get Self-Service User's Tickets GET - Returns all tickets for the logged-in user, with optional status filter

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_tickets.php` |
| Protected by | Signed-in portal user (session check) |
| Reads | `GET status` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | read [emails](/code/tables/emails.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [departments](/code/tables/departments.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)

