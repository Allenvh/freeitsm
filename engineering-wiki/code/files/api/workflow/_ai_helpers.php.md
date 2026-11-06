# api/workflow/_ai_helpers.php

> API endpoint - 249 lines

Workflows AI helpers — Stage 3 + Settings (#343).

The co-author turns a plain-English description into a structured workflow
proposal (trigger + conditions + actions) that the editor applies to the
canvas. Provider-aware: supports Anthropic (Claude) and OpenAI (GPT) so an
install can pick whichever it has an account with.

Per-module billing: the workflow co-author uses its OWN `workflow_ai_*`
keys in `system_settings`, not the CMDB ones. Configured under
**Workflow → Settings → AI**. The API key is encrypted at rest and masked
when returned to the client (same pattern RFP / Tickets / CMDB use).

Public surface:
  loadWorkflowAiConfig(PDO)        -> ['provider', 'model', 'api_key', 'verify_ssl']
  callWorkflowAi(`$cfg`, `$sys`, `$msg`) -> string (the assistant's reply text)
  parseClaudeJson(`$text`)           -> array (robust JSON extractor)

Lower-level provider drivers:
  wfCallAnthropic(...)
  wfCallOpenAI(...)

## The endpoint

| | |
|---|---|
| URL | `api/workflow/_ai_helpers.php` |
| Protected by | Helper - included by other endpoints, not called directly |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [workflowEffectiveSslVerify()](/code/functions/workflowEffectiveSslVerify.md) | 72 | Resolve the effective SSL_VERIFYPEER flag for an outbound curl call. |
| [loadWorkflowAiConfig()](/code/functions/loadWorkflowAiConfig.md) | 83 | Load the workflow-specific AI settings from `system_settings`. Throws if no key is configured so the caller can surface "configure your AI provider under Workflow → Settings → AI Integration first" to the user. |
| [callWorkflowAi()](/code/functions/callWorkflowAi.md) | 103 | Provider-agnostic one-shot call via the shared client (Anthropic / OpenAI / OpenRouter). Returns the assistant's response text (a plain string). |
| [wfCallAnthropic()](/code/functions/wfCallAnthropic.md) | 129 | Sends a system prompt and one user message to the Anthropic Messages API and returns the reply text, trimmed. |
| [wfCallOpenAI()](/code/functions/wfCallOpenAI.md) | 183 | Sends a system prompt and one user message to the OpenAI Chat Completions API and returns the reply text, trimmed. |
| [parseClaudeJson()](/code/functions/parseClaudeJson.md) | 234 | Pulls the JSON out of an AI reply - strips Markdown code fences and any text before the first { or [ - and decodes it. |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md)

**Included by:** [api/workflow/ai_compose.php](/code/files/api/workflow/ai_compose.php.md), [api/workflow/test_ai_key.php](/code/files/api/workflow/test_ai_key.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

