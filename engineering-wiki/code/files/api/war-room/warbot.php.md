# api/war-room/warbot.php

> API endpoint - 108 lines

API: War room — ask Warbot to answer a message.

POST JSON { message_id: <int> }

WHY THE CLIENT TRIGGERS THIS instead of send.php doing it inline: answering
takes a model round trip, sometimes several. Doing it inside send.php would
make every message in the room wait for it, including the ones not addressed to
Warbot. So the send returns immediately and the sender's browser asks for the
answer separately. There is no cron and no queue, which is the same reasoning
as retention pruning on write — an emergency tool must not depend on a
scheduled job somebody may never have set up.

⚠️ The consequence is that the trigger is UNTRUSTED and REPEATABLE: any analyst
in the channel could call it for any message id, and a retry or a double submit
could call it twice. So this endpoint re-derives everything from the message
itself — the channel, the asker, whether Warbot was addressed at all — and
refuses to answer the same message twice.

## The endpoint

| | |
|---|---|
| URL | `api/war-room/warbot.php` |
| Protected by | Module access: war-room (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md), [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [warbotAlreadyAnswered()](/code/functions/warbotAlreadyAnswered.md), [warbotAnswer()](/code/functions/warbotAnswer.md), [warbotIsAddressed()](/code/functions/warbotIsAddressed.md), [warbotPost()](/code/functions/warbotPost.md), [warRoomCanAccessChannel()](/code/functions/warRoomCanAccessChannel.md), [warRoomMessageRow()](/code/functions/warRoomMessageRow.md), [warRoomMessages()](/code/functions/warRoomMessages.md)

