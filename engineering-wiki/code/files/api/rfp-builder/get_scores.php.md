# api/rfp-builder/get_scores.php

> API endpoint - 144 lines

Return everything the scoring page needs for one (rfp, supplier, analyst) scoring session: every consolidated requirement grouped by category, the current analyst's existing scores (if any), and the average score from other analysts who have also scored this supplier (so the analyst can see how their judgment compares to the team without seeing individual names — single-blind on purpose).

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_scores.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET rfp_id`, `GET supplier_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 |  | read [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md), read [suppliers](/code/tables/suppliers.md) |  |
| 52 |  | read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 59 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 69 |  | read [rfp_scores](/code/tables/rfp_scores.md) |  |
| 87 |  | read [rfp_scores](/code/tables/rfp_scores.md) |  |
| 106 |  | read [rfp_scores](/code/tables/rfp_scores.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

