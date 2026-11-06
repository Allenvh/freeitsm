# api/contracts/delete_contract_term_tab.php

> API endpoint - 43 lines

API Endpoint: Delete contract term tab

## The endpoint

| | |
|---|---|
| URL | `api/contracts/delete_contract_term_tab.php` |
| Protected by | Module access: contracts (requireModuleAccessJson); Permission: Cap::CONTRACTS_CONTRACT_TERMS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | **delete** [contract_term_values](/code/tables/contract_term_values.md) |  |
| 33 |  | read [contract_term_tabs](/code/tables/contract_term_tabs.md) |  |
| 34 |  | **delete** [contract_term_tabs](/code/tables/contract_term_tabs.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

