# api/workflow/test_ai_key.php

> API endpoint - 175 lines

API: Send a tiny "say ok" prompt to the chosen provider so the user can verify their key + model BEFORE saving. Supports both Anthropic and OpenAI. If the masked placeholder ("****abcd") is submitted, the stored key is used instead — so the user can test the saved config without re-typing the secret.

Mirrors the test pattern from api/rfp-builder/test_ai_connection.php.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/test_ai_key.php` |
| Protected by | Module access: workflow (requireModuleAccessJson); Permission: Cap::WORKFLOW_AI (requireCapabilityJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [wfTestAnthropic()](/code/functions/wfTestAnthropic.md) | 85 | Sends a tiny prompt to the Anthropic Messages API to prove the key and model work, and returns the reply text with the token counts. |
| [wfTestOpenAI()](/code/functions/wfTestOpenAI.md) | 139 | Sends a tiny prompt to the OpenAI Chat Completions API to prove the key and model work, and returns the reply text with the token counts. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [api/workflow/_ai_helpers.php](/code/files/api/workflow/_ai_helpers.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [isMaskedNoChangeValue()](/code/functions/isMaskedNoChangeValue.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md), [workflowEffectiveSslVerify()](/code/functions/workflowEffectiveSslVerify.md)

