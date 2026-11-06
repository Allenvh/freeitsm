# api/cmdb/ai_suggest_properties.php

> API endpoint - 169 lines

API: Stage 2 of the "suggest properties" wizard. Given a class + the analyst's answers to the stage-1 questions, Claude returns 6-12 suggested properties tailored to their environment.

Returned properties are validated against the property-type allow-list and
the existing labels/keys before being sent back, so the client can show
them in a checklist UI for confirmation.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/ai_suggest_properties.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson); Permission: Cap::CMDB_CLASSES (requireCapabilityJson) |
| Called from | [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [slugifyProp()](/code/functions/slugifyProp.md) | 33 | Turns a property name into a lower-case key of letters, digits and underscores, falling back to 'property' when nothing is left. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 |  | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 55 |  | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [api/cmdb/_ai_helpers.php](/code/files/api/cmdb/_ai_helpers.php.md)

**Calls:** [anthropicResponseText()](/code/functions/anthropicResponseText.md), [callAnthropic()](/code/functions/callAnthropic.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [loadCmdbAiConfig()](/code/functions/loadCmdbAiConfig.md), [parseClaudeJson()](/code/functions/parseClaudeJson.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

