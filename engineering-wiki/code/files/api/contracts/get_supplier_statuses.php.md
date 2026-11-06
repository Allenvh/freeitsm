# api/contracts/get_supplier_statuses.php

> API endpoint - 42 lines

API Endpoint: Get supplier statuses

## The endpoint

| | |
|---|---|
| URL | `api/contracts/get_supplier_statuses.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [supplier_statuses](/code/tables/supplier_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

