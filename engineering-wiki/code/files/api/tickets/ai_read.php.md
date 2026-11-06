# api/tickets/ai_read.php

> API endpoint - 221 lines

API: Tickets — "read this ticket for me" (discussion #104, idea 12).

GET  ?ticket_id=N   the briefing already written, and whether it is behind
  POST { ticket_id }  write a new one

A different question from the maintained summary. The summary is a standing
description of where a ticket stands; this is what you want at the moment you
open one cold — what happened, and what should I do next.

─────────────────────────────────────────────────────────────────────────────
🔑 IT IS KEPT, AND IT IS VERSIONED.

The first version of this stored nothing at all, on the reasoning that a
stored suggestion becomes a fact the next person reads without knowing a
machine wrote it. Ed's answer, which is the better one: the actual experience
of the unstored version is waiting a minute for a briefing you have already
read. The "it becomes a fact" worry is answered exactly the way the summary
answers it — it carries the time it was written, what it read, an AI label,
and a line saying how many messages have arrived since.

So it shares the summary's table with `kind = 'read'` and inherits its rules:
a re-read writes version n+1, nothing is ever overwritten, and every earlier
version stays readable.

⚠️ Reopening it costs NOTHING. The GET answers from the table and never
touches the provider; only a re-read spends anything.

Uses the Tickets AI provider (ns=tickets_reply_cleanup).

## The endpoint

| | |
|---|---|
| URL | `api/tickets/ai_read.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 103 |  | read [ticket_ai_summaries](/code/tables/ticket_ai_summaries.md), read [analysts](/code/tables/analysts.md) |  |
| 186 |  | **insert** [ticket_ai_summaries](/code/tables/ticket_ai_summaries.md) (`ticket_id`, `kind`, `version`, `summary`, `provider`, `model`, `message_count`, `note_count`, `last_email_id`, `generated_by`, `tokens_in`, `tokens_out`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketAiAllowed()](/code/functions/ticketAiAllowed.md), [ticketAiLatestSummary()](/code/functions/ticketAiLatestSummary.md), [ticketAiMessagesSince()](/code/functions/ticketAiMessagesSince.md), [ticketAiReadSystemPrompt()](/code/functions/ticketAiReadSystemPrompt.md), [ticketAiSettings()](/code/functions/ticketAiSettings.md), [ticketAiTranscript()](/code/functions/ticketAiTranscript.md)

