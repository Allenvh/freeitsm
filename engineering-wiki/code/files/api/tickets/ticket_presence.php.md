# api/tickets/ticket_presence.php

> API endpoint - 75 lines

API: heartbeat + read, in one request.

POST { ticket_id, composing?, leave? } -> { success, others: [...], stale_seconds }

One endpoint does both halves deliberately: every client that wants to know
who else is here is, by definition, here itself — so splitting them would
double the request rate of the busiest poll in the product for no gain.

Module access only, and then `analystCanAccessTicket()`. Presence leaks the
NAMES of colleagues against a ticket reference, so the company scope has to
hold here exactly as it does on the ticket itself.

`leave` is fire-and-forget: it rides on navigator.sendBeacon during page
teardown, when the browser will not wait for a response and cannot report a
failure. Nothing depends on it arriving — a missed leave just means the
indicator fades on the stale window instead of instantly.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/ticket_presence.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [presenceHeartbeat()](/code/functions/presenceHeartbeat.md), [presenceLeave()](/code/functions/presenceLeave.md), [presenceOthers()](/code/functions/presenceOthers.md), [presencePurge()](/code/functions/presencePurge.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

