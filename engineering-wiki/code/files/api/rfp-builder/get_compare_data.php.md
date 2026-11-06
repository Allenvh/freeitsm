# api/rfp-builder/get_compare_data.php

> API endpoint - 170 lines

Aggregate scoring data for the cross-supplier compare page.

For each supplier: their per-category averages and an overall
average, computed via the same multi-analyst rollup the scoring
page uses:
  1. Per (supplier, requirement): mean across analysts who scored
  2. Per (supplier, category):    mean of those per-requirement
                                  means across requirements in the cat
  3. Overall per supplier:        mean of all per-requirement means

Categories with no scored requirements get null (not 0) so the
page can render "—" instead of pretending there's a zero.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_compare_data.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET rfp_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 52 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 67 |  | read [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md), read [suppliers](/code/tables/suppliers.md) |  |
| 79 |  | read [rfp_scores](/code/tables/rfp_scores.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

