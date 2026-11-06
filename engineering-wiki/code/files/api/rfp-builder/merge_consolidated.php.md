# api/rfp-builder/merge_consolidated.php

> API endpoint - 133 lines

Merge two or more consolidated requirements into one — used when the AI under-merged true duplicates. Caller supplies the IDs to merge and the merged row's text/priority/category. Source links from all the merged-away rows are union'd onto the new row (INSERT IGNORE handles the rare case of duplicate links).

Conflicts attached to the merged-away rows are deleted along with
those rows (cascade) — re-detection on next consolidation re-run
is the right recovery path.

All work happens inside one transaction so we never end up with a
partial merge.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/merge_consolidated.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 77 |  | read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 84 |  | read [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md) |  |
| 92 |  | **insert** [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) (`rfp_id`, `category_id`, `requirement_text`, `requirement_type`, `priority`, `ai_rationale`) |  |
| 101 |  | **insert** [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md) (`consolidated_id`, `extracted_id`) |  |
| 111 |  | **delete** [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 115 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

