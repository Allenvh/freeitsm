# api/messaging/ai_summary.php

> API endpoint - 79 lines

API Endpoint: AI summary of a messaging-channel conversation (WhatsApp etc.).

Summarises the conversation into a few sentences and saves it as an internal
note on the ticket, so the thread is digestible at a glance. Reuses the Tickets
AI key (ns = tickets_reply_cleanup).

## The endpoint

| | |
|---|---|
| URL | `api/messaging/ai_summary.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 71 |  | **insert** [ticket_notes](/code/tables/ticket_notes.md) (`ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [messagingBuildTranscript()](/code/functions/messagingBuildTranscript.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketAiAllowed()](/code/functions/ticketAiAllowed.md)

