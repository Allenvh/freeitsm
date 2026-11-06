# api/rfp-builder/invite_supplier.php

> API endpoint - 64 lines

Invite an existing supplier to an RFP. The schema's UQ(rfp_id, supplier_id) prevents duplicate invitations; we surface that as a friendly error rather than a PDO exception.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/invite_supplier.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [rfps](/code/tables/rfps.md) |  |
| 43 |  | read [suppliers](/code/tables/suppliers.md) |  |
| 47 |  | read [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) |  |
| 52 |  | **insert** [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) (`rfp_id`, `supplier_id`, `demo_date`, `notes`) |  |
| 57 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

