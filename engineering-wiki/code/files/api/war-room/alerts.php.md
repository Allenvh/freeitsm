# api/war-room/alerts.php

> API endpoint - 56 lines

API: War room — what is waiting for me.

GET  → { count: <int>, mentions: [ … ] }

WHY THIS EXISTS SEPARATELY FROM poll.php. A mention is only worth anything if it
reaches somebody who is NOT looking at the war room — otherwise it is decoration
on a page they were already reading. So this is called by the shared header, on
every page in FreeITSM, and it is built to be the cheapest request in the app:

  - one indexed lookup on (analyst_id, id), no joins the panel does not render;
  - a 60-second interval, not the war room's 3 seconds;
  - the header skips it entirely on the war room page itself, which already
    polls and would otherwise ask twice;
  - it stops while the tab is hidden.

That budget matters more here than usual: this runs on every page during exactly
the incident when the rest of the app is busiest, which is the same reasoning
that ruled out SSE for the chat itself.

⚠️ Returns an empty result rather than 403 for an analyst without the module, so
the header never has to know who does. The header renders nothing for count 0.

## The endpoint

| | |
|---|---|
| URL | `api/war-room/alerts.php` |
| Protected by | Signed-in analyst (session check) (via includes/i18n.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [warRoomMyMentions()](/code/functions/warRoomMyMentions.md)

