# api/rfp-builder/get_available_suppliers.php

> API endpoint - 53 lines

List active suppliers not yet invited to a given RFP — feeds the "invite supplier" picker.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_available_suppliers.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET rfp_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [suppliers](/code/tables/suppliers.md), read [supplier_statuses](/code/tables/supplier_statuses.md), read [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

