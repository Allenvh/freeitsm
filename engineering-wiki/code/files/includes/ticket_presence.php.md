# includes/ticket_presence.php

> PHP - 175 lines

Collision detection — who else is in this ticket right now.

WHY THIS EXISTS
---------------
Two analysts working the same ticket without knowing it is the oldest
service-desk annoyance there is: both write a reply, the customer gets two
answers that may contradict each other, and one analyst's ten minutes are
gone. It happens most on a busy morning, and most of all in Unassigned, where
several people triage the same list at once. Nothing in FreeITSM warned you.

PRESENCE IS A HEARTBEAT, NOT A SESSION
--------------------------------------
There is no "join" and no "leave" that has to succeed. An open ticket sends a
heartbeat every few seconds; a row is "live" only while `last_seen` is within
PRESENCE_STALE_SECONDS. So a closed tab, a killed browser, a flat battery or a
dropped network all resolve themselves in one stale window with nothing having
to run — the same reasoning that made snooze's waking need no cron (#933).

The explicit leave (sent on navigating away) is an OPTIMISATION: it makes the
indicator disappear at once rather than in half a minute. Nothing depends on
it arriving, which is why it can ride on `sendBeacon` and be forgotten about.

WHAT IT IS NOT
--------------
It is not a lock. Two people may legitimately need to write on one ticket —
an escalation and a holding reply, say — so this warns and never blocks. A
lock would need an owner, a timeout, a way to steal it and a way to explain
why the button is greyed out, and would be wrong most of the time it fired.

It is also not an audit trail. Rows are overwritten by the next heartbeat and
deleted without ceremony; "who looked at this ticket last Tuesday" is a
different feature with different privacy questions, and this table cannot
answer it.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [presenceSchemaReady()](/code/functions/presenceSchemaReady.md) | 58 | Does this database have the presence table yet? |
| [presenceHeartbeat()](/code/functions/presenceHeartbeat.md) | 78 | Record that this analyst is on this ticket. Upserts onto the (ticket, analyst) unique key, so a ticket left open all afternoon is one row, not a thousand. |
| [presenceOthers()](/code/functions/presenceOthers.md) | 99 | Everyone ELSE currently on this ticket, most recently seen first. |
| [presenceLeave()](/code/functions/presenceLeave.md) | 131 | Drop this analyst's row — the optimisation that makes leaving instant. |
| [presencePurge()](/code/functions/presencePurge.md) | 153 | Bin rows nobody will ever read again. |
| [presenceInitials()](/code/functions/presenceInitials.md) | 166 | "Sarah Williams" -> "SW". Mirrors the header avatar in includes/waffle-menu.php. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 82 | `presenceHeartbeat` | **insert** [ticket_presence](/code/tables/ticket_presence.md) (`ticket_id`, `analyst_id`, `last_seen`, `is_composing`) |  |
| 103 | `presenceOthers` | read [ticket_presence](/code/tables/ticket_presence.md), read [analysts](/code/tables/analysts.md) |  |
| 135 | `presenceLeave` | **delete** [ticket_presence](/code/tables/ticket_presence.md) |  |
| 139 | `presenceLeave` | **delete** [ticket_presence](/code/tables/ticket_presence.md) |  |
| 156 | `presencePurge` | **delete** [ticket_presence](/code/tables/ticket_presence.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md)

**Included by:** [api/tickets/ticket_presence.php](/code/files/api/tickets/ticket_presence.php.md)

