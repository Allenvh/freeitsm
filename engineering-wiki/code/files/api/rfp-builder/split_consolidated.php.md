# api/rfp-builder/split_consolidated.php

> API endpoint - 147 lines

Split one consolidated requirement back into N — used when the AI over-merged subtly different items. Caller supplies the original row id and an array of new rows, each with its own text/priority/ category and the subset of the original's source-extracted IDs that should belong to it.

Source IDs that aren't assigned to any new row are dropped (the
analyst can add them back via "Add custom" if needed). Source
IDs not on the original row are silently ignored.

The original row's conflicts are deleted along with the row —
conflicts referenced "this row" as A or B and that identity is
gone, so re-detection on next consolidation re-run is the right
recovery path. Inside one transaction so we never end up with
half-split state.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/split_consolidated.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 61 |  | read [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md) |  |
| 81 |  | read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 111 |  | **insert** [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) (`rfp_id`, `category_id`, `requirement_text`, `requirement_type`, `priority`, `ai_rationale`) |  |
| 116 |  | **insert** [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md) (`consolidated_id`, `extracted_id`) |  |
| 131 |  | **delete** [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 134 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

