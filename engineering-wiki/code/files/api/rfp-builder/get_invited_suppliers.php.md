# api/rfp-builder/get_invited_suppliers.php

> API endpoint - 54 lines

List suppliers invited to an RFP, joined to the suppliers table for legal/trading name and to the supplier_statuses lookup so the page can show status badges. Most-recently-invited first.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_invited_suppliers.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET rfp_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 |  | read [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md), read [suppliers](/code/tables/suppliers.md), read [supplier_statuses](/code/tables/supplier_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

