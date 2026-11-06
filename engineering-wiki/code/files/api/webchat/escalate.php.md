# api/webchat/escalate.php

> API endpoint - 77 lines

PUBLIC endpoint: the visitor escalates an AI (deflect-mode) conversation to a human.

mode = 'agent' — promote the chat to a live ticket (the transcript becomes the
                   opening message) and hand it to the team. Further messages behave
                   like any webchat ticket, and analyst replies stream back by polling.
  mode = 'email' — create a ticket with an AI summary as the body and the full chat
                   log attached as a .txt, then tell the visitor it'll be answered by
                   email. (The reply itself — outbound email — is the offline-email
                   piece; this endpoint only opens the ticket.)

Idempotent: once the conversation has a ticket, this just reports success. Needs the
conversation token, and only acts when the widget's AI + the chosen route are enabled.

## The endpoint

| | |
|---|---|
| URL | `api/webchat/escalate.php` |
| Protected by | Public - no sign-in check found (sign-in pages, webhooks and token links are meant to be; anything else, check the code) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/webchat/public.php](/code/files/includes/webchat/public.php.md), [includes/webchat/ai.php](/code/files/includes/webchat/ai.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [webchatAddMessage()](/code/functions/webchatAddMessage.md), [webchatAiSummarise()](/code/functions/webchatAiSummarise.md), [webchatFail()](/code/functions/webchatFail.md), [webchatHandlePreflight()](/code/functions/webchatHandlePreflight.md), [webchatLoadConversation()](/code/functions/webchatLoadConversation.md), [webchatPromoteToTicket()](/code/functions/webchatPromoteToTicket.md), [webchatPublicResolve()](/code/functions/webchatPublicResolve.md), [webchatTranscriptText()](/code/functions/webchatTranscriptText.md)

