# api/rfp-builder/create_prospective_supplier.php

> API endpoint - 99 lines

Create a new "prospective" supplier inline from the RFP suppliers page and immediately invite them to the current RFP. Used when the supplier the analyst wants to evaluate isn't yet in FreeITSM's suppliers list.

The supplier is created with just legal_name (and optional trading_name
+ comments) — analyst can flesh out the record from the suppliers
module later if the procurement progresses.

Returns the new supplier_id and the resulting invitation_id.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/create_prospective_supplier.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 47 |  | read [rfps](/code/tables/rfps.md) |  |
| 55 |  | read [supplier_statuses](/code/tables/supplier_statuses.md) |  |
| 64 |  | **insert** [suppliers](/code/tables/suppliers.md) (`legal_name`, `trading_name`, `supplier_status_id`, `comments`, `is_active`) |  |
| 76 |  | **insert** [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) (`rfp_id`, `supplier_id`, `demo_date`, `notes`) |  |
| 82 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

