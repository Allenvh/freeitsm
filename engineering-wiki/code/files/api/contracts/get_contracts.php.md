# api/contracts/get_contracts.php

> API endpoint - 96 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Customer Contracts Developer Guide](https://github.com/edmozley/freeitsm/wiki/Customer-Contracts-Developer-Guide)

API: the contracts list - the active company's contracts, or every company the analyst can see under "All companies".

Newest first. The search matches contract number, title, supplier names and, once
customer contracts exist, the customer company or person.

## The endpoint

| | |
|---|---|
| URL | `api/contracts/get_contracts.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET search`, `GET limit`, `GET party` |
| Input | search  optional text to match |
| Input | limit   optional maximum number of rows |
| Input | party   optional 'supplier' or 'customer' - only that kind of contract |
| Output | {success, contracts: [...]} - each with party_label |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [analysts](/code/tables/analysts.md), read [contract_statuses](/code/tables/contract_statuses.md), read [payment_schedules](/code/tables/payment_schedules.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md), [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [contractListSql()](/code/functions/contractListSql.md), [contractOwnerSql()](/code/functions/contractOwnerSql.md), [contractPartyLabel()](/code/functions/contractPartyLabel.md), [contractPartyReady()](/code/functions/contractPartyReady.md), [contractPartySql()](/code/functions/contractPartySql.md), [costCentreSelectSql()](/code/functions/costCentreSelectSql.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

