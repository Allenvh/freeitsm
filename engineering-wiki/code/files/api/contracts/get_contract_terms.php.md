# api/contracts/get_contract_terms.php

> API endpoint - 32 lines

API Endpoint: Get contract term values for a contract

## The endpoint

| | |
|---|---|
| URL | `api/contracts/get_contract_terms.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET contract_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 24 |  | read [contract_term_values](/code/tables/contract_term_values.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

