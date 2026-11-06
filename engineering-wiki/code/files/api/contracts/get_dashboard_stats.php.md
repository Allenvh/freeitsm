# api/contracts/get_dashboard_stats.php

> API endpoint - 58 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Customer Contracts Developer Guide](https://github.com/edmozley/freeitsm/wiki/Customer-Contracts-Developer-Guide)

API: the counts on the Contracts dashboard - contracts, active, customer, expiring within 90 days, suppliers and contacts.

The contract counts cover the same contracts as the list beside them
(contractListSql): the active company, or all the analyst can see. Supplier and
contact counts are install-wide.

## The endpoint

| | |
|---|---|
| URL | `api/contracts/get_dashboard_stats.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Output | {success, stats: {contracts, active_contracts, customer_contracts, suppliers, contacts, expiring_soon}} |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 |  | read [contracts](/code/tables/contracts.md) |  |
| 45 |  | read [suppliers](/code/tables/suppliers.md) |  |
| 48 |  | read [contacts](/code/tables/contacts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [contractListSql()](/code/functions/contractListSql.md), [contractPartyReady()](/code/functions/contractPartyReady.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

