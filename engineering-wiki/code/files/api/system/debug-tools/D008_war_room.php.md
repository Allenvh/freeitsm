# api/system/debug-tools/D008_war_room.php

> API endpoint - 370 lines

D008 — War room health.

⚠️ THIS MATTERS MORE THAN A HEALTH CHECK USUALLY DOES, because the war room is
a BREAK-GLASS feature. Every other module tells you it is broken the moment you
try to use it. This one gets opened for the first time during an incident, when
the usual chat is already down and nobody has any appetite for discovering that
the attachments folder is unwritable or that the all-hands channel was never
created. The entire value of this tool is that it can be run on a quiet
Tuesday.

So it checks the things that fail SILENTLY:
  - a missing all-hands channel: the war room opens with nowhere to talk
  - a foreign key with the wrong delete rule: deleting one analyst quietly
    destroys the record of an incident, and nobody finds out until the review
  - an unwritable or unprotected attachments folder
  - files on disk with no row pointing at them (retention deletes rows; only
    the sweep deletes bytes)
  - Warbot's tools, which are the half that must work with no internet

⚠️ Unlike D007 this needs no probe row left behind: the end-to-end write test
runs inside a TRANSACTION and is rolled back. D007 could not do that because
MATCH...AGAINST cannot see uncommitted rows; nothing here has that problem, so
nothing here should flash up in somebody's open war room.

Output: plain text, section-delimited with === HEADERS === for easy skimming.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D008_war_room.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 65 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [yn()](/code/functions/yn.md) | 74 | Formats a boolean as YES or NO for the report. |
| [emit_and_exit()](/code/functions/emit_and_exit.md) | 82 | Adds the VERDICT section - the numbered problems found, or an all-clear - then prints every section as plain text and stops. |
| [warroomD008Bytes()](/code/functions/warroomD008Bytes.md) | 364 | Formats a byte count as B, KB or MB for the report. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 177 |  | read [warroom_channels](/code/tables/warroom_channels.md) |  |
| 186 |  | read [warroom_channels](/code/tables/warroom_channels.md) |  |
| 187 |  | read [warroom_channels](/code/tables/warroom_channels.md) |  |
| 194 |  | read [teams](/code/tables/teams.md), read [warroom_channels](/code/tables/warroom_channels.md) |  |
| 201 |  | read [warroom_channels](/code/tables/warroom_channels.md) |  |
| 229 |  | read [warroom_attachments](/code/tables/warroom_attachments.md) |  |
| 257 |  | read [warroom_messages](/code/tables/warroom_messages.md) |  |
| 311 |  | read [warroom_channels](/code/tables/warroom_channels.md) |  |
| 332 |  | read [warroom_messages](/code/tables/warroom_messages.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md), [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md)

**Calls:** [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [warbotRunTool()](/code/functions/warbotRunTool.md), [warbotTools()](/code/functions/warbotTools.md), [warRoomMessages()](/code/functions/warRoomMessages.md), [warRoomPresent()](/code/functions/warRoomPresent.md), [warRoomRetentionDays()](/code/functions/warRoomRetentionDays.md), [warRoomSend()](/code/functions/warRoomSend.md), [warRoomTouchPresence()](/code/functions/warRoomTouchPresence.md)

