# api/tickets/get_ticket_attachments.php

> API endpoint - 78 lines

API Endpoint: Get attachments for a ticket Returns all attachments from all emails in a ticket

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_attachments.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

