# api/tickets/ai_summary.php

> API endpoint - 246 lines

API: Tickets — the maintained AI summary (discussion #104, idea 7).

GET  ?ticket_id=N              the current summary, and whether it is behind
  GET  ?ticket_id=N&history=1    every version ever written, newest first
  POST { ticket_id, auto?:bool } write a new version

─────────────────────────────────────────────────────────────────────────────
🔑 A REFRESH NEVER OVERWRITES.

It writes version n+1 and leaves n alone. A summary is a machine's reading of
a conversation and a later reading can be worse than an earlier one — the
model changes, the thread outgrows the window, somebody tightens the prompt.
If the newest version were the only one, that loss would be silent. The
history is one click away in the panel and nothing ever deletes it.

⚠️ `auto` is not a licence to spend. An automatic refresh is refused unless
the administrator has switched it on AND the conversation has actually moved
the configured number of messages ahead of the last version. The browser asks
for it on open; the SERVER decides, because a browser that asks twice must
not be able to bill twice.

Uses the Tickets AI provider (ns=tickets_reply_cleanup) — one API key for the
module rather than a fourth panel to configure.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/ai_summary.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id`, `GET history` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 89 |  | read [ticket_ai_summaries](/code/tables/ticket_ai_summaries.md), read [analysts](/code/tables/analysts.md) |  |
| 207 |  | **insert** [ticket_ai_summaries](/code/tables/ticket_ai_summaries.md) (`ticket_id`, `version`, `summary`, `provider`, `model`, `message_count`, `note_count`, `last_email_id`, `generated_by`, `tokens_in`, `tokens_out`, `truncated`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketAiAllowed()](/code/functions/ticketAiAllowed.md), [ticketAiLatestSummary()](/code/functions/ticketAiLatestSummary.md), [ticketAiMessagesSince()](/code/functions/ticketAiMessagesSince.md), [ticketAiSettings()](/code/functions/ticketAiSettings.md), [ticketAiSummarySystemPrompt()](/code/functions/ticketAiSummarySystemPrompt.md), [ticketAiTranscript()](/code/functions/ticketAiTranscript.md)

