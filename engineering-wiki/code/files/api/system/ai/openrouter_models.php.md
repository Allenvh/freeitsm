# api/system/ai/openrouter_models.php

> API endpoint - 44 lines

API: Return the (cached) OpenRouter model catalogue for the model picker. GET ?refresh=1 forces a re-fetch. Returns: { success, models:[{id,name,context_length,prompt_price,completion_price}], cached_at, stale }

## The endpoint

| | |
|---|---|
| URL | `api/system/ai/openrouter_models.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/settings_keys.php) |
| Reads | `GET refresh` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), [includes/ai_provider.php](/code/files/includes/ai_provider.php.md)

**Calls:** [aiProviderListOpenRouterModels()](/code/functions/aiProviderListOpenRouterModels.md), [analystCanManageAnyAiNamespace()](/code/functions/analystCanManageAnyAiNamespace.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

