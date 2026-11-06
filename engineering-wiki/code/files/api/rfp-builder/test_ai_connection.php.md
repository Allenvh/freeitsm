# api/rfp-builder/test_ai_connection.php

> API endpoint - 183 lines

Send a tiny "say ok" prompt to the chosen provider to verify that the API key + model are valid. The user can submit a fresh key to test BEFORE saving; passing the masked placeholder ("****abcd") falls back to the stored key.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/test_ai_connection.php` |
| Protected by | Module access: contracts (requireModuleAccessJson); Permission: Cap::CONTRACTS_RFP_AI (requireCapabilityJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [rfpTestAnthropic()](/code/functions/rfpTestAnthropic.md) | 83 | Sends a tiny prompt to the Anthropic Messages API to prove the key and model work, and returns the reply text with the token counts. |
| [rfpTestOpenAI()](/code/functions/rfpTestOpenAI.md) | 142 | Sends a tiny prompt to the OpenAI Chat Completions API to prove the key and model work, and returns the reply text with the token counts. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [isMaskedNoChangeValue()](/code/functions/isMaskedNoChangeValue.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

