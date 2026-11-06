# api/contracts/save_contract_terms.php

> API endpoint - 27 lines

API Endpoint: Save contract term values (bulk upsert for a contract). Thin UI adapter over ContractsService.

## The endpoint

| | |
|---|---|
| URL | `api/contracts/save_contract_terms.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/contracts.php](/code/files/includes/services/contracts.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

