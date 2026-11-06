# api/notifications/get_notifications.php

> API endpoint - 45 lines

API: the current analyst's notifications, plus the unread count.

Polled by the bell in the header on every page, so it is deliberately one
request rather than a list call and a count call.

GET ?count_only=1 returns just the badge number — what the poll uses between
openings of the dropdown.

## The endpoint

| | |
|---|---|
| URL | `api/notifications/get_notifications.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET count_only` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/notifications.php](/code/files/includes/services/notifications.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

