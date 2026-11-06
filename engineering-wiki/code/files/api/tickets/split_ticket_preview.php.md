# api/tickets/split_ticket_preview.php

> API endpoint - 73 lines

API Endpoint: what a split WOULD move, or the full list to pick from.

GET ?ticket_id=&list_all=1
  -> { messages: [...], total_on_ticket }   // every movable message, to tick from
GET ?ticket_id=&from_email_id=&include_newer=0|1
  -> { messages: [...], total_on_ticket, would_empty: bool }   // the anchor path

The dialog lets the analyst tick individual messages, so it needs the whole movable
set (list_all) — markers excluded, because you cannot split a marker off on its own.
The anchor path stays for the "select newer" helper's count. Either way the messages
are read from the server, not counted in JavaScript: a dialog that tallied them
itself would eventually disagree with the server, and an analyst would confirm a move
they had not been shown.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/split_ticket_preview.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id`, `GET list_all`, `GET from_email_id`, `GET include_newer` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [emails](/code/tables/emails.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_split.php](/code/files/includes/ticket_split.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [splitMarkerEmailIds()](/code/functions/splitMarkerEmailIds.md), [splitMessagesFrom()](/code/functions/splitMessagesFrom.md), [splitMovableCount()](/code/functions/splitMovableCount.md)

