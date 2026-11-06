# api/workflow/ai_compose.php

> API endpoint - 284 lines

API: AI co-author — compose a workflow from a natural-language description.

Body shape:
  { prompt: string, existing?: { trigger_event, conditions: [...], actions: [...] } }

Returns:
  { success: true, workflow: { name, description, trigger_event,
                               conditions: [...], actions: [...] },
                   explanation: string }

Stage 3 MVP: single round-trip, non-streaming. Streaming a la claude.ai is
planned for a follow-up. The system prompt gives Claude the engine's
catalogues so it can only propose triggers/operators/actions the engine
actually understands; the response is validated against the same catalogues
server-side before being returned to the client, with unknown items dropped
(logged in `warnings`) rather than failing the whole compose.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/ai_compose.php` |
| Protected by | Module access: workflow (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [api/workflow/_ai_helpers.php](/code/files/api/workflow/_ai_helpers.php.md)

**Calls:** [callWorkflowAi()](/code/functions/callWorkflowAi.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [loadWorkflowAiConfig()](/code/functions/loadWorkflowAiConfig.md), [parseClaudeJson()](/code/functions/parseClaudeJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

