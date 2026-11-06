# api/contracts/get_contract_term_tabs.php

> API endpoint - 30 lines

API Endpoint: Get contract term tabs

## The endpoint

| | |
|---|---|
| URL | `api/contracts/get_contract_term_tabs.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 19 |  | read [contract_term_tabs](/code/tables/contract_term_tabs.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

