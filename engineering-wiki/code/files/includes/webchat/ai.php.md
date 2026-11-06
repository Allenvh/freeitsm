# includes/webchat/ai.php

> PHP - 122 lines

Web chat AI answers — draft a reply from the Knowledge base for a visitor's message.

Reuses the shared KB retrieval (includes/knowledge/kb_ai.php) + the shared AI client
(aiProviderChat) with the SAME provider/key the Knowledge module's AI chat uses
(namespace 'knowledge_ai'). The system prompt is webchat-specific: warm, short, and
strictly grounded in the retrieved articles — it must NOT invent answers, and it points
the visitor at a human when unsure (the widget renders the actual escalation buttons).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [webchatAiConfigured()](/code/functions/webchatAiConfigured.md) | 18 | Is a Knowledge AI provider/key configured? (Cheap check before spending the budget.) |
| [webchatAiReply()](/code/functions/webchatAiReply.md) | 44 | Draft an AI answer to `$question`, given the recent `$history` (array of ['sender','body']). Returns ['ok'=>bool, 'answer'=>string, 'articles'=>[{id,title}], 'error'=>?string]. |
| [webchatAiSummarise()](/code/functions/webchatAiSummarise.md) | 97 | Summarise a web chat transcript into a short support-ticket description for a human agent — used as the ticket body on an "email me back" escalation. Returns '' when AI is unconfigured or the call fails, so the caller can fall back to the raw transcript. |

## How it hangs together

**Includes:** [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md), [includes/knowledge/audience.php](/code/files/includes/knowledge/audience.php.md)

**Included by:** [api/webchat/escalate.php](/code/files/api/webchat/escalate.php.md), [api/webchat/send.php](/code/files/api/webchat/send.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [kbBuildContext()](/code/functions/kbBuildContext.md), [kbRetrieveArticles()](/code/functions/kbRetrieveArticles.md)

