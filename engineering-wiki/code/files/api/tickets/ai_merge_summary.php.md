# api/tickets/ai_merge_summary.php

> API endpoint - 225 lines

API: Tickets — AI merge summary (streaming).

POST { ticket_id: int }  — the MERGED ticket, after the merge has happened.
Streams Server-Sent Events as the model reads the combined conversation and
writes two short sections: what the requesters asked for, and what has already
been asked or done by the service desk.

WHY THIS EXISTS
---------------
Merging four tickets about one outage produces one ticket with four interleaved
conversations. The next analyst to open it has to read all of them to learn what
is essentially two facts: what people want, and what has been tried. That is the
gap this fills — it is a reading aid, not a record.

WHICH IS WHY IT IS WRITTEN AS A NOTE, NOT AS A MESSAGE
-----------------------------------------------------
The summary is a machine's opinion about a conversation. It goes in as an internal
note (never shared with the requester), it says on its face that AI wrote it, and
an analyst can edit or delete it like any other note. It must never be mistaken
for something a person asserted, and it must never be sent to a customer.

The caller streams for the live typing effect and then POSTs the finished text to
save_merge_summary.php. Deliberately two steps: if the analyst closes the tab
halfway through, a half-written summary simply never gets saved.

Reuses the reply-cleanup AI settings (ns=tickets_reply_cleanup) rather than adding
a fourth provider block to configure — one AI key for the Tickets module.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/ai_merge_summary.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sse_send()](/code/functions/sse_send.md) | 60 | Sends one server-sent event (an event name and a JSON data line) and flushes it to the browser straight away. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 106 |  | read [emails](/code/tables/emails.md) |  |
| 117 |  | read [ticket_notes](/code/tables/ticket_notes.md), read [analysts](/code/tables/analysts.md) |  |
| 133 |  | read [ticket_merges](/code/tables/ticket_merges.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), [includes/ai_provider.php](/code/files/includes/ai_provider.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpAiCallAnthropicStreaming()](/code/functions/rfpAiCallAnthropicStreaming.md), [ticketAiAllowed()](/code/functions/ticketAiAllowed.md)

