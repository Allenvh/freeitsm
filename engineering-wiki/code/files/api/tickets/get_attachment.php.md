# api/tickets/get_attachment.php

> API endpoint - 123 lines

API Endpoint: Serve email attachment Returns attachment file by ID or content_id

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_attachment.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET id`, `GET cid`, `GET email_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | read [email_attachments](/code/tables/email_attachments.md) |  |
| 39 |  | read [email_attachments](/code/tables/email_attachments.md) |  |
| 61 |  | read [emails](/code/tables/emails.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [attachmentSendHeaders()](/code/functions/attachmentSendHeaders.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

