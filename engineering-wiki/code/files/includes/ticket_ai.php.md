# includes/ticket_ai.php

> PHP - 304 lines

Reading a whole ticket with an AI: the shared parts.

Two features from discussion #104 sit on this file:

  • The MAINTAINED SUMMARY (idea 7) — a few lines at the top of the ticket
    saying where things stand, rewritten when the conversation moves on.
  • READ THIS TICKET FOR ME (idea 12) — an on-demand briefing you ask for and
    read once, stored nowhere.

They share one thing worth having in a single place: turning a ticket into the
text a model actually sees. Getting that wrong is how a summary quotes an
internal note into something customer-facing, or costs ten times what it
should because nobody bounded the transcript.

─────────────────────────────────────────────────────────────────────────────
⚠️ WHAT A SUMMARY IS NOT

It is not the ticket. The conversation stays exactly where it was, in full,
underneath — the summary is an extra pane you can collapse, never a
replacement for reading. That matters more here than anywhere else in
FreeITSM, because this is the one feature that could quietly become the only
thing anybody reads. So it is always labelled as machine-written, always
carries the time it was written and what it read, and always says when the
conversation has moved on since.

🔑 AND IT IS NEVER OVERWRITTEN. A refresh writes a new version; every earlier
one stays readable. A later reading really can be worse than an earlier one —
a model changes underneath you, a thread grows past what fits — and without
history that loss is silent and permanent.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ticketAiSettings()](/code/functions/ticketAiSettings.md) | 44 | SETTINGS |
| [ticketAiTranscript()](/code/functions/ticketAiTranscript.md) | 121 | Turn a ticket into the plain text a model reads. |
| [ticketAiMessagesSince()](/code/functions/ticketAiMessagesSince.md) | 207 | How many messages have arrived since the summary was written. |
| [ticketAiLatestSummary()](/code/functions/ticketAiLatestSummary.md) | 229 | The newest stored summary (or briefing) for a ticket, or null. |
| [ticketAiSummarySystemPrompt()](/code/functions/ticketAiSummarySystemPrompt.md) | 254 | The instructions for the standing summary. |
| [ticketAiReadSystemPrompt()](/code/functions/ticketAiReadSystemPrompt.md) | 284 | "Read this ticket for me" asks a different question from the summary. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 71 | `ticketAiSettings` | read [system_settings](/code/tables/system_settings.md) |  |
| 132 | `ticketAiTranscript` | read [emails](/code/tables/emails.md) |  |
| 144 | `ticketAiTranscript` | read [emails](/code/tables/emails.md) |  |
| 151 | `ticketAiTranscript` | read [ticket_notes](/code/tables/ticket_notes.md), read [analysts](/code/tables/analysts.md) |  |
| 210 | `ticketAiMessagesSince` | read [emails](/code/tables/emails.md) |  |
| 216 | `ticketAiMessagesSince` | read [emails](/code/tables/emails.md) |  |
| 235 | `ticketAiLatestSummary` | read [ticket_ai_summaries](/code/tables/ticket_ai_summaries.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/ai_settings.php](/code/files/includes/ai_settings.php.md)

**Included by:** [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md), [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md), [tickets/index.php](/code/files/tickets/index.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

