# api/war-room/attachment.php

> API endpoint - 55 lines

API: War room — serve an attachment.

GET ?id=<int>

🔒 TWO RULES, AND NEITHER IS OPTIONAL.

1. AUTHORISATION IS THE CHANNEL'S. `warRoomAttachmentFor()` resolves the file
   through its message to its channel and applies exactly the same access rule
   as reading the conversation. Guessing an attachment id from a DM you are not
   in returns 404, not the file. The check is in the service layer rather than
   here so there is one answer to "may I read this", shared with every other
   caller.

2. THE CONTENT TYPE IS OURS, NOT THE UPLOADER'S. `attachmentSendHeaders()` works
   it out from the file extension against ATTACHMENT_SERVE_TYPES and adds
   nosniff (security finding F5). This is why there is no content_type column
   to consult: an attachment that could declare itself image/svg+xml could run
   script in the reader's session, because SVG is XML and the browser opens it
   as a top-level document on our own origin.

The bytes live outside any directory the web server will execute from — see
includes/uploads.php — but that is the net under the tightrope, not the
tightrope. Nothing is ever fetched from that folder directly.

## The endpoint

| | |
|---|---|
| URL | `api/war-room/attachment.php` |
| Protected by | Module access: war-room (requireModuleAccessJson) |
| Reads | `GET id` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md)

**Calls:** [attachmentSendHeaders()](/code/functions/attachmentSendHeaders.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [warRoomAttachmentFor()](/code/functions/warRoomAttachmentFor.md)

