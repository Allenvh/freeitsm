# api/system/recent_trail.php

> API endpoint - 75 lines

API: this analyst's recent trail, grouped into headings (discussion #124). GET — no parameters. Always the caller's own trail; there is nothing to ask for.

⚠️ NOT GATED ON ONE MODULE, for the same reason record_preview.php is not:
eight kinds of record are reachable from here and each needs a different gate,
so the gating lives in `entityRecentTrail()` — module gate first, then the
record gate, per type. Naming a single module here would either refuse an
analyst their own trail or wave through a type that module has nothing to do
with. Being signed in is the whole of the gate at this level, and it is enough:
the only thing this endpoint can ever return is the caller's own history,
re-filtered through today's permissions.

🔴 THE FILTERING IS THE POINT, not a formality. The trail is written when a
record is opened and read possibly weeks later, so it is the ONLY list in the
product guaranteed to hold references that have since been deleted or put out
of reach. Rows that no longer resolve are dropped in silence — a "you can no
longer see this" row would confirm the record exists, which is the fact the
check withholds.

⚠️ LAZY BY DESIGN. The waffle drawer is on all 91 screens; resolving a trail
into every page render would put this work on every page load in the product
to serve a pane most of them never open. So the drawer ships empty markup and
calls this the first time the Recent tab is opened.

## The endpoint

| | |
|---|---|
| URL | `api/system/recent_trail.php` |
| Protected by | Signed-in analyst (session check) (via includes/i18n.php); Signed-in analyst (session check) (via includes/recent_trail.php) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [recentTrailIso()](/code/functions/recentTrailIso.md) | 71 | 'YYYY-MM-DD HH:MM:SS' as stored (UTC) → an ISO instant the browser can read. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/recent_trail.php](/code/files/includes/recent_trail.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [entityRecentTrail()](/code/functions/entityRecentTrail.md)

