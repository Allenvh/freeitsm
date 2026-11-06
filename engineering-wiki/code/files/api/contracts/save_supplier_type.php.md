# api/contracts/save_supplier_type.php

> API endpoint - 52 lines

API Endpoint: Save supplier type (create or update)

## The endpoint

| | |
|---|---|
| URL | `api/contracts/save_supplier_type.php` |
| Protected by | Module access: contracts (requireModuleAccessJson); Permission: Cap::CONTRACTS_SUPPLIER_TYPES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | **update** [supplier_types](/code/tables/supplier_types.md) (`name`, `description`, `display_order`, `is_active`) |  |
| 39 |  | **insert** [supplier_types](/code/tables/supplier_types.md) (`name`, `description`, `display_order`, `is_active`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

