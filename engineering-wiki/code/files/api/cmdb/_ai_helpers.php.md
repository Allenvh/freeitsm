# api/cmdb/_ai_helpers.php

> API endpoint - 83 lines

Shared CMDB AI helpers — load saved settings, call Anthropic, parse JSON. Keeps the per-feature endpoints thin and consistent.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/_ai_helpers.php` |
| Protected by | Helper - included by other endpoints, not called directly |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [loadCmdbAiConfig()](/code/functions/loadCmdbAiConfig.md) | 17 | Load CMDB AI config via the shared building block (ns=cmdb_ai), then attach the CMDB-specific custom instructions. Returns provider/model/api_key/ verify_ssl/custom_instructions. Throws if no key is set. |
| [callAnthropic()](/code/functions/callAnthropic.md) | 41 | One-shot AI call via the shared, provider-agnostic client (Anthropic / OpenAI / OpenRouter). Name kept for backward compatibility with callers. Returns ['content' => string, 'tokens_in' => ?int, 'tokens_out' => ?int, ...]. |
| [anthropicResponseText()](/code/functions/anthropicResponseText.md) | 54 | Pull the assistant's text out of the shared client's response shape. |
| [parseClaudeJson()](/code/functions/parseClaudeJson.md) | 64 | Parse a JSON response from Claude robustly: strip markdown fences, extract the first JSON object/array, and json_decode it. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 23 | `loadCmdbAiConfig` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md)

**Included by:** [api/cmdb/ai_suggest_properties.php](/code/files/api/cmdb/ai_suggest_properties.php.md), [api/cmdb/ai_suggest_questions.php](/code/files/api/cmdb/ai_suggest_questions.php.md), [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md), [api/lms/ai_author.php](/code/files/api/lms/ai_author.php.md), [api/problem-management/ai_root_cause.php](/code/files/api/problem-management/ai_root_cause.php.md), [api/problem-management/ai_suggest_problem.php](/code/files/api/problem-management/ai_suggest_problem.php.md), [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md)

