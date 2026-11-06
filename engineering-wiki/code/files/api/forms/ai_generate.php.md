# api/forms/ai_generate.php

> API endpoint - 352 lines

API: Forms — AI Assist (streaming)

POST { description: string }
Streams Server-Sent Events as the AI generates a JSON form definition.

Events emitted:
  text  { delta: "..." }              token chunks (the raw JSON, useful for live preview)
  usage { tokens_in, tokens_out, ... } token counters
  done  { form: { title, description, fields: [...] }, duration_ms, tokens_in, tokens_out }
  error { message: "..." }

Per-module billing (#436): config now resolves from `forms_ai_*`
system_settings entries via `loadFormsAiConfig()` so admins can use a
different API key for the Forms AI than for the RFP Builder, and
the spend shows up against the Forms feature in their provider
console. The streaming helper from rfp_ai.php is still reused but
with a settingsOverride so it picks up the forms-specific config.

## The endpoint

| | |
|---|---|
| URL | `api/forms/ai_generate.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sse_send()](/code/functions/sse_send.md) | 50 | Sends one server-sent event (an event name and a JSON data line) and flushes it to the browser straight away. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md), [api/forms/_ai_helpers.php](/code/files/api/forms/_ai_helpers.php.md), [includes/ai_provider.php](/code/files/includes/ai_provider.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [loadFormsAiConfig()](/code/functions/loadFormsAiConfig.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpAiCallAnthropicStreaming()](/code/functions/rfpAiCallAnthropicStreaming.md)

