# api/system/search_rebuild.php

> API endpoint - 82 lines

API: rebuild the search index, one slice at a time (discussion #53).

⚠️ WHY IT IS CHUNKED
The rebuild is the one operation here that scales with the size of the
installation. On a small one it takes under a second; on years of tickets it
would run past max_execution_time and die halfway, leaving a partly-rebuilt
index and no way to tell how far it got. So the client calls this repeatedly:
each call indexes a slice of tickets and reports where it stopped, and the
caller passes that back as `since_ticket_id`.

That also gives an honest progress bar for free, rather than a spinner that
cannot say whether anything is happening.

Knowledge articles are a separate pass and are done on the LAST slice only —
they are not keyed by ticket id, so including them in every slice would redo
all of them every time.

Rebuilding is always SAFE: every write is an upsert keyed on
(source_type, source_id), so a slice that runs twice updates in place.

## The endpoint

| | |
|---|---|
| URL | `api/system/search_rebuild.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/search/backfill.php](/code/files/includes/search/backfill.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [searchBackfillPrune()](/code/functions/searchBackfillPrune.md), [searchBackfillRun()](/code/functions/searchBackfillRun.md)

