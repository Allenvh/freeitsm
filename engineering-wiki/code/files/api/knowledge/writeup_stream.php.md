# api/knowledge/writeup_stream.php

> API endpoint - 268 lines

API: Knowledge — write this up (streaming).

POST { ticket_id?: int, cluster_id?: int, answers?: string }

One endpoint, two entry points. The Tickets module sends a ticket_id (the
analyst just solved something and pressed the button); the Knowledge assistant
sends a cluster_id (this question has been asked fourteen times). Both run the
same engine, because the judgement — is there actually an article here? — is
identical either way.

SSE events:
  verdict {verdict:'article'|'not_enough'}  as soon as the first line lands
  text    {delta}                            token by token after that
  done    {verdict, questions[], explanation, tokens...}
  error   {message}

⚠️ THE VERDICT ARRIVES FIRST AND IS BUFFERED FOR. The model's opening line is
a machine-readable verdict, so the front end must not render anything until it
has been read and stripped — otherwise the words "VERDICT: ARTICLE" appear at
the top of the analyst's draft. Everything up to the first newline is held
back here; from then on deltas pass straight through.

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/writeup_stream.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sse_send()](/code/functions/sse_send.md) | 56 | Sends one server-sent event (an event name and a JSON data line) and flushes it to the browser straight away. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 109 |  | read [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |
| 133 |  | read [knowledge_gap_cluster_tickets](/code/tables/knowledge_gap_cluster_tickets.md), read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), [includes/ai_provider.php](/code/files/includes/ai_provider.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [aiProviderChat()](/code/functions/aiProviderChat.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpAiCallAnthropicStreaming()](/code/functions/rfpAiCallAnthropicStreaming.md), [ticketAiAllowed()](/code/functions/ticketAiAllowed.md), [ticketAiExclusionSql()](/code/functions/ticketAiExclusionSql.md), [writeupAiConfig()](/code/functions/writeupAiConfig.md), [writeupClusterUserMessage()](/code/functions/writeupClusterUserMessage.md), [writeupExtractExplanation()](/code/functions/writeupExtractExplanation.md), [writeupExtractQuestions()](/code/functions/writeupExtractQuestions.md), [writeupParseResponse()](/code/functions/writeupParseResponse.md), [writeupSchemaReady()](/code/functions/writeupSchemaReady.md), [writeupSystemPrompt()](/code/functions/writeupSystemPrompt.md), [writeupTicketBundle()](/code/functions/writeupTicketBundle.md), [writeupUserMessage()](/code/functions/writeupUserMessage.md)

