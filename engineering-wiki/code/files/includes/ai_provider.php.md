# includes/ai_provider.php

> PHP - 866 lines

Reusable, storage-agnostic AI provider client.

One place that knows how to send a chat/completion request to any of the
supported providers and normalise the response. It takes a plain config
array (provider/model/key/...) — it does NOT read settings itself, so it
can be reused by any module. The companion `ai_settings.php` loads config
from system_settings and hands it here.

Providers:
  - anthropic   → POST https://api.anthropic.com/v1/messages
  - openai      → POST https://api.openai.com/v1/chat/completions
  - openrouter  → POST https://openrouter.ai/api/v1/chat/completions
                  (OpenAI-wire-compatible; one key reaches hundreds of
                   models, model ids are namespaced e.g. "anthropic/claude-3.5-sonnet")
  - azure       → POST {endpoint}/openai/deployments/{deployment}/chat/completions
                       ?api-version={version}
                  Azure OpenAI's DEPLOYMENT-BASED endpoints (discussion #86).
                  Same request and response shape as `openai`; three things
                  differ, and they are the whole of the new code:
                    1. the URL names a DEPLOYMENT you created in your own
                       Azure tenant, and carries a REQUIRED query string
                    2. the key goes in an `api-key:` header, NOT
                       `Authorization: Bearer`
                    3. the deployment IS the model, so no `model` is sent
                  Asked for by organisations whose governance will not let
                  traffic leave their own Azure tenant — for them this is not
                  a preference, it is the only way to switch AI on at all.
                  An API version of "v1" uses Azure's newer route instead,
                  {endpoint}/openai/v1/chat/completions, which takes no
                  query string and DOES want the deployment as `model`
                  (GH #171).

Request shapes mirror the proven ones in includes/rfp_ai.php; this file is
intentionally independent so the RFP builder isn't affected.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [aiProviderChat()](/code/functions/aiProviderChat.md) | 66 | Send a one-shot chat request and return a normalised result. |
| [aiProviderCallAnthropic()](/code/functions/aiProviderCallAnthropic.md) | 160 | Sends one system + user prompt to the Anthropic Messages API and returns the joined text blocks with token counts and the stop reason. |
| [aiAzureChatUrl()](/code/functions/aiAzureChatUrl.md) | 213 | Build the URL for an Azure OpenAI deployment-based chat call. |
| [aiAzureIsV1()](/code/functions/aiAzureIsV1.md) | 245 | An API version of "v1" means Azure's newer route, which has no api-version. |
| [aiAzureApiVersionLooksValid()](/code/functions/aiAzureApiVersionLooksValid.md) | 262 | Whether an API version looks like one Azure publishes. |
| [aiProviderCallOpenAICompatible()](/code/functions/aiProviderCallOpenAICompatible.md) | 291 | The OpenAI chat-completions wire format, at whatever URL and behind whatever auth header the caller names. |
| [aiOpenAICompatPost()](/code/functions/aiOpenAICompatPost.md) | 373 | POST an OpenAI-format chat request, putting right the two parameters newer models refuse by name. |
| [aiProviderTraceStart()](/code/functions/aiProviderTraceStart.md) | 404 | Record every HTTP exchange this file makes until `aiProviderTraceTake()`. |
| [aiProviderTraceTake()](/code/functions/aiProviderTraceTake.md) | 410 | Stop recording and return what was recorded. |
| [aiExplainFailure()](/code/functions/aiExplainFailure.md) | 426 | What a failed call most likely means, in a sentence an administrator can act on - or '' when the error says it plainly enough already. |
| [aiProviderChatTools()](/code/functions/aiProviderChatTools.md) | 505 | ─── Tool calling ──────────────────────────────────────────────────────────── |
| [aiToolsResult()](/code/functions/aiToolsResult.md) | 701 | Shared return shape for aiProviderChatTools. |
| [aiProviderHttpPost()](/code/functions/aiProviderHttpPost.md) | 723 | POST with retry/backoff on 429 / 5xx / network errors. Ported from rfpAiHttpPostWithRetry so this file stands alone. |
| [aiProviderListOpenRouterModels()](/code/functions/aiProviderListOpenRouterModels.md) | 791 | Fetch (and cache) the OpenRouter model catalogue. No API key required. Cached in system_settings as JSON for 24h to keep the model picker snappy. Falls back to a stale cache if a refresh fetch fails. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 794 | `aiProviderListOpenRouterModels` | read [system_settings](/code/tables/system_settings.md) |  |
| 846 | `aiProviderListOpenRouterModels` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md)

**Included by:** [api/forms/ai_generate.php](/code/files/api/forms/ai_generate.php.md), [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md), [api/system/ai/openrouter_models.php](/code/files/api/system/ai/openrouter_models.php.md), [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md), [api/tickets/ai_merge_summary.php](/code/files/api/tickets/ai_merge_summary.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), [includes/webchat/ai.php](/code/files/includes/webchat/ai.php.md)

**Calls:** [sslApplyCurl()](/code/functions/sslApplyCurl.md)

