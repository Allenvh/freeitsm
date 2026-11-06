# api/tickets/get_ticket_thread.php

> API endpoint - 212 lines

API Endpoint: Get all emails for a ticket (for building reply thread) Returns emails ordered by received_datetime ASC

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_thread.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [stripQuotedThread()](/code/functions/stripQuotedThread.md) | 178 | Strip quoted/nested thread content from an email body Relies on our own visible marker text, with generic blockquote fallback |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [emails](/code/tables/emails.md) |  |
| 143 |  | read [tickets](/code/tables/tickets.md) |  |
| 150 |  | read [emails](/code/tables/emails.md), read [messaging_channels](/code/tables/messaging_channels.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [channelHasServiceWindow()](/code/functions/channelHasServiceWindow.md), [channelWindowOpen()](/code/functions/channelWindowOpen.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

