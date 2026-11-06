# api/messaging/ai_suggest_reply.php

> API endpoint - 69 lines

API Endpoint: AI-suggested reply for a messaging-channel ticket (WhatsApp etc.).

Reads the conversation and drafts a short, friendly reply for the analyst to
review/edit before sending. Does NOT send anything. Reuses the Tickets AI key
(ns = tickets_reply_cleanup) so it shares that feature's provider + billing line.

## The endpoint

| | |
|---|---|
| URL | `api/messaging/ai_suggest_reply.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [messagingBuildTranscript()](/code/functions/messagingBuildTranscript.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketAiAllowed()](/code/functions/ticketAiAllowed.md)

