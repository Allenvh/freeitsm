# api/tickets/get_mailbox_whitelist.php

> API endpoint - 37 lines

API Endpoint: Get whitelist entries for a mailbox GET: ?mailbox_id=N

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_mailbox_whitelist.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET mailbox_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [mailbox_email_whitelist](/code/tables/mailbox_email_whitelist.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

