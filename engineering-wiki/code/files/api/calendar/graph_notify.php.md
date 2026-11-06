# api/calendar/graph_notify.php

> API endpoint - 117 lines

Microsoft Graph change-notification endpoint (GH #75).

Graph POSTs here when a subscribed calendar changes, so a move or a deletion
lands in seconds rather than on the next poll.

🔴 THIS URL IS PUBLIC AND UNAUTHENTICATED BY NECESSITY. Microsoft's servers
call it and cannot carry a session or an API key. Three things keep it safe,
and all three matter:

  1. clientState — a random secret per subscription, echoed in every
     notification. A payload without a MATCHING one is discarded. This is what
     separates Graph from anyone on the internet who finds the URL.
  2. The body is never trusted for CONTENT. A notification says only "this
     subscription saw a change"; what actually changed is then read from Graph
     ourselves, with the app's own credentials. So a forged notification can
     at worst cause a poll of a calendar we already sync.
  3. Nothing here writes a ticket directly. It calls the same pull path as the
     cron, with the same guards — baseline, deletion cap, audit.

⚠️ THE VALIDATION HANDSHAKE MUST BE FAST AND UNCONDITIONAL. When a
subscription is created Graph immediately GETs/POSTs here with
?validationToken=… and expects that exact string back, as text/plain, within
ten seconds. It happens BEFORE any subscription exists, so it cannot be
authenticated by clientState — echoing a token proves only that we control the
URL, which is precisely what it is for.

🔴 AND IT MUST COME BEFORE THE require LINES. Graph sends that validation POST
declaring `Content-Type: text/plain`, and includes/request_guard.php — reached
via functions.php, running on include — answers 415 to exactly that. Two
correct pieces of code, colliding: the guard refuses text/plain because no
browser has an honest reason to send it, and Graph sends it because the body is
empty and the token is in the query string. Put the handshake below the
includes and it never executes; Graph reports only "Subscription validation
request failed … UnsupportedMediaType" and no subscription is ever created.

Answering above the guard is safe because this branch does nothing: no session,
no database, no cookie read, no state changed. It echoes one query parameter as
inert text/plain (nosniff, so no browser can be talked into running it) and
exits. There is nothing here for a forged request to achieve.

## The endpoint

| | |
|---|---|
| URL | `api/calendar/graph_notify.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET validationToken` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 93 |  | read [calendar_enrolments](/code/tables/calendar_enrolments.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md)

**Calls:** [calendarSyncPullForAnalyst()](/code/functions/calendarSyncPullForAnalyst.md), [calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

