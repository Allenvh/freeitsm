# api/webchat/send.php

> API endpoint - 126 lines

PUBLIC endpoint: a visitor sends a message.

Two shapes, chosen by the widget's config:

 • Plain widget (ai_enabled = 0) — the message is ingested straight onto the ticket
   membrane (creating the ticket on the first message), exactly like an email or
   WhatsApp message. When outside business hours it still becomes a ticket, and the
   offline message is returned so the visitor knows it'll be answered later.

 • AI widget (ai_enabled = 1) — webchat_messages is the visitor-facing transcript.
   The message is recorded there, and (in assist mode, or once escalated, or when
   closed) also ingested onto a ticket so an analyst has the full picture. While open
   and not yet handed to a human, the KB-grounded AI drafts a reply.

Needs the conversation token.

## The endpoint

| | |
|---|---|
| URL | `api/webchat/send.php` |
| Protected by | Public - no sign-in check found (sign-in pages, webhooks and token links are meant to be; anything else, check the code) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/webchat/public.php](/code/files/includes/webchat/public.php.md), [includes/webchat/ai.php](/code/files/includes/webchat/ai.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [webchatAddMessage()](/code/functions/webchatAddMessage.md), [webchatAiReply()](/code/functions/webchatAiReply.md), [webchatFail()](/code/functions/webchatFail.md), [webchatGetMessages()](/code/functions/webchatGetMessages.md), [webchatHandlePreflight()](/code/functions/webchatHandlePreflight.md), [webchatIngestMessage()](/code/functions/webchatIngestMessage.md), [webchatInsertOutbound()](/code/functions/webchatInsertOutbound.md), [webchatIsOpenNow()](/code/functions/webchatIsOpenNow.md), [webchatLoadConversation()](/code/functions/webchatLoadConversation.md), [webchatOfflineMessage()](/code/functions/webchatOfflineMessage.md), [webchatPublicResolve()](/code/functions/webchatPublicResolve.md), [webchatRateLimitSend()](/code/functions/webchatRateLimitSend.md)

