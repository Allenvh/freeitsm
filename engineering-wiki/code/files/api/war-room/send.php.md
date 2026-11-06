# api/war-room/send.php

> API endpoint - 113 lines

API: War room — post a message, with or without attachments.

POST application/json           { channel_id: <int>, body: "<text>" }
POST multipart/form-data        channel_id, body, files[]

Both shapes land here rather than in two endpoints, because a message with a
screenshot on it is still one message: splitting them would mean a half-sent
message existing whenever the second request failed.

Returns the message id. The page does not render the response — it lets the
next poll bring the message back, so what you see is what everyone else sees
rather than an optimistic local copy that might not have saved.

## The endpoint

| | |
|---|---|
| URL | `api/war-room/send.php` |
| Protected by | Module access: war-room (requireModuleAccessJson) |
| Reads | `POST channel_id`, `POST body` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [warRoomCanPostChannel()](/code/functions/warRoomCanPostChannel.md), [warRoomSend()](/code/functions/warRoomSend.md), [warRoomStoreAttachment()](/code/functions/warRoomStoreAttachment.md)

