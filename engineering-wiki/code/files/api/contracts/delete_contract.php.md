# api/contracts/delete_contract.php

> API endpoint - 24 lines

Thin UI adapter over ContractsService — delete a contract (+ its term values).

## The endpoint

| | |
|---|---|
| URL | `api/contracts/delete_contract.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/contracts.php](/code/files/includes/services/contracts.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

