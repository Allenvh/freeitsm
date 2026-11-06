# api/rfp-builder/save_score.php

> API endpoint - 84 lines

Upsert one analyst's score for a (rfp, supplier, requirement). Score can be NULL (the analyst explicitly hasn't scored this requirement yet, or chose N/A) or 0-5. Notes are an optional free-text field.

The schema's UQ(rfp_id, supplier_id, analyst_id, consolidated_id)
means there's exactly one row per (analyst, requirement) pair —
this endpoint keeps it that way via INSERT...ON DUPLICATE KEY UPDATE.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/save_score.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 61 |  | read [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) |  |
| 67 |  | **insert** [rfp_scores](/code/tables/rfp_scores.md) (`rfp_id`, `supplier_id`, `analyst_id`, `consolidated_id`, `score`, `notes`) |  |
| 77 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

