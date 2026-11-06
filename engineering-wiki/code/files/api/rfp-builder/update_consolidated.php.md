# api/rfp-builder/update_consolidated.php

> API endpoint - 76 lines

Update a single consolidated requirement — text, type, priority, category, AI rationale. The analyst can hand-tune any of these after the AI's first pass. category_id is validated against the RFP's own categories; null is allowed (uncategorised).

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/update_consolidated.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 56 |  | read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 62 |  | **update** [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) (`requirement_text`, `requirement_type`, `priority`, `category_id`, `ai_rationale`, `updated_datetime`) |  |
| 69 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

