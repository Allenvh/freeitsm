# api/self-service/get_recording.php

> API endpoint - 135 lines

API: Stream a screen recording with HTTP Range support.

Dual auth: either the end user who owns the ticket OR any logged-in analyst.
Range requests are honoured so the <video> element can seek without
downloading the entire file first.

Query: id — ticket_recordings.id

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_recording.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET id` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [ticket_recordings](/code/tables/ticket_recordings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/managers.php](/code/files/includes/managers.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md)

