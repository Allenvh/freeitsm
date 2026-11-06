# api/cmdb/ai_suggest_questions.php

> API endpoint - 101 lines

API: Stage 1 of the "suggest properties" wizard. Given a class, ask Claude for 3-5 short clarifying questions that will sharpen the property suggestions in stage 2.

Different organisations run very different stacks — the questions exist
so we don't end up suggesting Oracle properties for someone's MongoDB.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/ai_suggest_questions.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson); Permission: Cap::CMDB_CLASSES (requireCapabilityJson) |
| Called from | [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 39 |  | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [api/cmdb/_ai_helpers.php](/code/files/api/cmdb/_ai_helpers.php.md)

**Calls:** [anthropicResponseText()](/code/functions/anthropicResponseText.md), [callAnthropic()](/code/functions/callAnthropic.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [loadCmdbAiConfig()](/code/functions/loadCmdbAiConfig.md), [parseClaudeJson()](/code/functions/parseClaudeJson.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

