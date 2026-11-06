# api/contracts/delete_supplier_type.php

> API endpoint - 45 lines

API Endpoint: Delete supplier type

## The endpoint

| | |
|---|---|
| URL | `api/contracts/delete_supplier_type.php` |
| Protected by | Module access: contracts (requireModuleAccessJson); Permission: Cap::CONTRACTS_SUPPLIER_TYPES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | **update** [suppliers](/code/tables/suppliers.md) (`supplier_type_id`) |  |
| 33 |  | read [supplier_types](/code/tables/supplier_types.md) |  |
| 34 |  | **delete** [supplier_types](/code/tables/supplier_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

