# api/contracts/save_contract_term_tab.php

> API endpoint - 50 lines

API Endpoint: Save contract term tab (create or update)

## The endpoint

| | |
|---|---|
| URL | `api/contracts/save_contract_term_tab.php` |
| Protected by | Module access: contracts (requireModuleAccessJson); Permission: Cap::CONTRACTS_CONTRACT_TERMS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | **update** [contract_term_tabs](/code/tables/contract_term_tabs.md) (`name`, `description`, `display_order`, `is_active`) |  |
| 39 |  | **insert** [contract_term_tabs](/code/tables/contract_term_tabs.md) (`name`, `description`, `display_order`, `is_active`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

