# api/war-room/poll.php

> API endpoint - 78 lines

API: War room — fetch new messages, register presence, refresh the sidebar.

GET  ?channel_id=<int>&since_id=<int>&read=<0|1>
  since_id = the newest id the caller already holds; 0 asks for the tail.
  read=1   = also mark the channel read up to the newest message returned.

This one request does four jobs — messages, presence in, presence out, and the
channel list with its unread counts — because it is the request the page makes
every few seconds anyway. Separate endpoints would multiply the traffic during
exactly the incident the module exists for.

POLLING, NOT SSE, deliberately. Apache + mod_php holds one process per open
connection, so thirty analysts each keeping an EventSource open would sit on
thirty workers indefinitely — during an incident, when the rest of the app is
busiest. A short poll is stateless and degrades gracefully.

## The endpoint

| | |
|---|---|
| URL | `api/war-room/poll.php` |
| Protected by | Module access: war-room (requireModuleAccessJson) |
| Reads | `GET channel_id`, `GET since_id`, `GET read` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [warRoomCanAccessChannel()](/code/functions/warRoomCanAccessChannel.md), [warRoomChannelList()](/code/functions/warRoomChannelList.md), [warRoomMarkRead()](/code/functions/warRoomMarkRead.md), [warRoomMentionCounts()](/code/functions/warRoomMentionCounts.md), [warRoomMessages()](/code/functions/warRoomMessages.md), [warRoomPresent()](/code/functions/warRoomPresent.md), [warRoomTouchPresence()](/code/functions/warRoomTouchPresence.md)

