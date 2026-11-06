# api/tickets/get_mailbox_outbound.php

> API endpoint - 100 lines

API Endpoint: Get the outbound send log for a mailbox GET: ?mailbox_id=N&search=text&status=failed&page=1

The outbound counterpart to get_mailbox_activity.php. Same shape of response so the
activity modal can render either tab with the same paging code.

mailbox_id=0 is meaningful, not a mistake: sends that never resolved a mailbox are
logged with a NULL mailbox_id, and those are among the most useful rows in the table
("no mailbox could send this"). Without a way to ask for them they would be written
and never seen.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_mailbox_outbound.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET mailbox_id`, `GET search`, `GET status`, `GET page` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 61 |  | read [email_send_log](/code/tables/email_send_log.md) |  |
| 65 |  | read [email_send_log](/code/tables/email_send_log.md) |  |
| 84 |  | read [email_send_log](/code/tables/email_send_log.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/email_log.php](/code/files/includes/email_log.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [emailLogRouteLabel()](/code/functions/emailLogRouteLabel.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

