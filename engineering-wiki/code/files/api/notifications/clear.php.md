# api/notifications/clear.php

> API endpoint - 55 lines

API: remove notifications from the bell (discussion #111).

POST { ids: [1,2,3] }                      — clear those
POST { all: true }                         — clear everything ALREADY READ
POST { all: true, include_unread: true }   — clear everything, unread included

A hard DELETE. Mark-as-read and clear are deliberately separate actions:
"read" silences the badge and keeps the row, "clear" removes it. Before this
existed, Mark all read left every row sitting in the panel for good, which is
what the discussion reported.

⚠️ include_unread must be asked for explicitly. Defaulting it off is the safety
catch — one click on Clear all should never bin news nobody has read yet.

Always scoped to the signed-in analyst, so ids belonging to somebody else are
simply not matched by the DELETE rather than being rejected — nothing leaks
either way, and a stale open tab does not error.

## The endpoint

| | |
|---|---|
| URL | `api/notifications/clear.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/notifications.php](/code/files/includes/services/notifications.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

