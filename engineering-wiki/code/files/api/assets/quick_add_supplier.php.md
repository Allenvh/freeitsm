# api/assets/quick_add_supplier.php

> API endpoint - 52 lines

API Endpoint: Quick-add a supplier from the Asset Settings Suppliers tab.

Creates a minimal row in the shared suppliers registry (legal_name only),
already flagged supplies_assets so it appears in the asset dropdown. Fuller
details (VAT, address, type/status, contacts) can be filled in later in the
Contracts module. If a supplier with the same legal name already exists, it
is simply flagged rather than duplicated.

## The endpoint

| | |
|---|---|
| URL | `api/assets/quick_add_supplier.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_SUPPLIERS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 |  | read [suppliers](/code/tables/suppliers.md) |  |
| 41 |  | **update** [suppliers](/code/tables/suppliers.md) (`supplies_assets`, `is_active`) |  |
| 44 |  | **insert** [suppliers](/code/tables/suppliers.md) (`legal_name`, `supplies_assets`, `is_active`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

