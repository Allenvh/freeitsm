# api/tickets/ai_cleanup_reply.php

> API endpoint - 220 lines

API: Tickets — AI Reply Cleanup (streaming)

POST { ticket_id: int, draft_text: string }
Streams Server-Sent Events as Claude rewrites the rough draft into a
properly formatted reply. The output is plain text with blank-line paragraph
breaks; the front-end is responsible for wrapping into <p> tags before
inserting into TinyMCE.

Reuses the rfp_ai.php streaming helper but supplies its own settings
(key + model) from the tickets_reply_cleanup_* keys so this feature has
its own line on the Anthropic billing dashboard.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/ai_cleanup_reply.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sse_send()](/code/functions/sse_send.md) | 46 | Sends one server-sent event (an event name and a JSON data line) and flushes it to the browser straight away. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 105 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 123 |  | read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |
| 146 |  | read [emails](/code/tables/emails.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/reply_cleanup_prompt.php](/code/files/includes/reply_cleanup_prompt.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), [includes/ai_provider.php](/code/files/includes/ai_provider.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [buildReplyCleanupSystemPrompt()](/code/functions/buildReplyCleanupSystemPrompt.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [replyCleanupToneDescription()](/code/functions/replyCleanupToneDescription.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpAiCallAnthropicStreaming()](/code/functions/rfpAiCallAnthropicStreaming.md), [ticketAiAllowed()](/code/functions/ticketAiAllowed.md)

