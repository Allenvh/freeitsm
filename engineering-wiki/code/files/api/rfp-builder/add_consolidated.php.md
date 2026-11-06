# api/rfp-builder/add_consolidated.php

> API endpoint - 108 lines

Add a custom consolidated requirement that the AI missed entirely. Optionally link to existing extracted source items (rare — usually the analyst is adding something extracted didn't catch either).

Auto-marked as "manually added" via ai_rationale = "Manually added".

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/add_consolidated.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 |  | read [rfps](/code/tables/rfps.md) |  |
| 58 |  | read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 68 |  | read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md) |  |
| 79 |  | **insert** [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) (`rfp_id`, `category_id`, `requirement_text`, `requirement_type`, `priority`, `ai_rationale`) |  |
| 88 |  | **insert** [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md) (`consolidated_id`, `extracted_id`) |  |
| 95 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

