# api/assets/search_suppliers.php

> API endpoint - 46 lines

API Endpoint: Search the shared suppliers registry (for the Asset Settings Suppliers tab). Returns every supplier with its supplies_assets flag so the tab can toggle which ones are available to pick on an asset.

## The endpoint

| | |
|---|---|
| URL | `api/assets/search_suppliers.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET search` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 23 |  | read [suppliers](/code/tables/suppliers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

