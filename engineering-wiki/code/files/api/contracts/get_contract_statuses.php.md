# api/contracts/get_contract_statuses.php

> API endpoint - 30 lines

API Endpoint: Get contract statuses

## The endpoint

| | |
|---|---|
| URL | `api/contracts/get_contract_statuses.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 19 |  | read [contract_statuses](/code/tables/contract_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

