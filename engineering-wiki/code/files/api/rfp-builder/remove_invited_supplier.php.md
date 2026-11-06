# api/rfp-builder/remove_invited_supplier.php

> API endpoint - 69 lines

Remove a supplier from an RFP's invitation list. Cascade-deletes any scores already submitted for that (rfp, supplier) pair via the FK on rfp_scores — there's no cascade to suppliers, so the supplier record itself stays put for use on other RFPs.

The schema doesn't actually cascade rfp_scores -> rfp_invited_suppliers
(rfp_scores has its own supplier_id FK to suppliers). So we manually
tidy up rfp_scores in the same transaction to keep the page state
coherent.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/remove_invited_supplier.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) |  |
| 50 |  | **delete** [rfp_scores](/code/tables/rfp_scores.md) |  |
| 53 |  | **delete** [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) |  |
| 56 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

