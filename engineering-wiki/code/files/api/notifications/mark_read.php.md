# api/notifications/mark_read.php

> API endpoint - 46 lines

API: mark notifications read.

POST { ids: [1,2,3] }  — mark those
POST { all: true }     — mark everything unread

Always scoped to the signed-in analyst, so ids belonging to somebody else are
simply not matched by the UPDATE rather than being rejected — there is nothing
to leak either way, and it keeps a stale open tab from erroring.

## The endpoint

| | |
|---|---|
| URL | `api/notifications/mark_read.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/notifications.php](/code/files/includes/services/notifications.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

