# api/contracts/delete_contract_status.php

> API endpoint - 45 lines

API Endpoint: Delete contract status

## The endpoint

| | |
|---|---|
| URL | `api/contracts/delete_contract_status.php` |
| Protected by | Module access: contracts (requireModuleAccessJson); Permission: Cap::CONTRACTS_CONTRACT_STATUSES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | **update** [contracts](/code/tables/contracts.md) (`contract_status_id`) |  |
| 33 |  | read [contract_statuses](/code/tables/contract_statuses.md) |  |
| 34 |  | **delete** [contract_statuses](/code/tables/contract_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

