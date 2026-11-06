# api/contracts/get_contract.php

> API endpoint - 78 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Customer Contracts Developer Guide](https://github.com/edmozley/freeitsm/wiki/Customer-Contracts-Developer-Guide)

API: one contract, with its supplier or customer, owner, status, payment schedule and cost centre.

A customer contract for a company the analyst cannot see is answered as "not found",
like one that does not exist (contractVisibilitySql).

## The endpoint

| | |
|---|---|
| URL | `api/contracts/get_contract.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET id` |
| Input | id  the contract |
| Output | {success, contract} - contract carries party_label and multi_company (show the company only when there is a choice) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [analysts](/code/tables/analysts.md), read [contract_statuses](/code/tables/contract_statuses.md), read [payment_schedules](/code/tables/payment_schedules.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md), [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [contractOwnerSql()](/code/functions/contractOwnerSql.md), [contractPartyLabel()](/code/functions/contractPartyLabel.md), [contractPartySql()](/code/functions/contractPartySql.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [costCentreSelectSql()](/code/functions/costCentreSelectSql.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

