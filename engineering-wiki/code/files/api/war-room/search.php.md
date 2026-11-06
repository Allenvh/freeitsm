# api/war-room/search.php

> API endpoint - 52 lines

API: War room — search the conversations you can see.

GET ?q=<text>&channel_id=<int|blank>

Scoping happens inside the query (see warRoomSearch), not by filtering results
afterwards. Filtering afterwards is the version that looks correct and quietly
starves the result set when the matches sit in channels you cannot read.

## The endpoint

| | |
|---|---|
| URL | `api/war-room/search.php` |
| Protected by | Module access: war-room (requireModuleAccessJson) |
| Reads | `GET q`, `GET channel_id` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [warRoomCanAccessChannel()](/code/functions/warRoomCanAccessChannel.md), [warRoomSearch()](/code/functions/warRoomSearch.md)

