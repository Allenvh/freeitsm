# api/tickets/get_mailbox_activity.php

> API endpoint - 68 lines

API Endpoint: Get activity log for a mailbox GET: ?mailbox_id=N&search=text&page=1

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_mailbox_activity.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET mailbox_id`, `GET search`, `GET page` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | read [mailbox_activity_log](/code/tables/mailbox_activity_log.md) |  |
| 49 |  | read [mailbox_activity_log](/code/tables/mailbox_activity_log.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

