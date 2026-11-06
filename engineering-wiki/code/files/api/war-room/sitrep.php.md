# api/war-room/sitrep.php

> API endpoint - 123 lines

API: War room — the situation report.

POST JSON { since: '<HH:MM>'|'<hours>h', channel_id?: <int> }

WHAT THIS IS FOR. During an incident somebody has to send an update to the
business every half hour, and they cannot read four hundred messages first.
This reads the chat since a time they choose and drafts the briefing: where
things stand, what changed, who is doing what, what is still unknown, and a
paragraph they could send as-is.

⚠️ THE IRONY IS ACKNOWLEDGED. The war room exists for when the internet is
down, and this is the one part of it that needs the internet. So it is strictly
additive: the chat never calls it, never waits for it, and does not degrade
without it. When the provider is unreachable this returns a plain "could not
reach the AI provider" and the room carries on exactly as before. That is also
why it is a panel you open rather than something on the page by default.

🔒 The transcript is scoped to the channels the CALLER can read, in SQL — so a
service delivery manager's summary can never quote a DM they are not in, which
would be a novel way to leak one.

## The endpoint

| | |
|---|---|
| URL | `api/war-room/sitrep.php` |
| Protected by | Module access: war-room (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [warRoomCanAccessChannel()](/code/functions/warRoomCanAccessChannel.md), [warRoomSitrepPrompt()](/code/functions/warRoomSitrepPrompt.md), [warRoomTranscriptSince()](/code/functions/warRoomTranscriptSince.md)

