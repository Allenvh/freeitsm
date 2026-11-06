# api/rfp-builder/update_invited_supplier.php

> API endpoint - 52 lines

Update an existing invitation row — demo date and notes. The supplier itself isn't editable here (that's the suppliers module); this endpoint covers per-RFP fields only.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/update_invited_supplier.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | read [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) |  |
| 41 |  | **update** [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) (`demo_date`, `notes`) |  |
| 45 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

