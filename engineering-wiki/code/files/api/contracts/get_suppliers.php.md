# api/contracts/get_suppliers.php

> API endpoint - 45 lines

API: every supplier, with its type and status names, sorted by legal name.

## The endpoint

| | |
|---|---|
| URL | `api/contracts/get_suppliers.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Output | {success, suppliers: [...]} |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 22 |  | read [suppliers](/code/tables/suppliers.md), read [supplier_types](/code/tables/supplier_types.md), read [supplier_statuses](/code/tables/supplier_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

