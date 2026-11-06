# api/rfp-builder/get_rfps.php

> API endpoint - 69 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Contracts](https://github.com/edmozley/freeitsm/wiki/Contracts)

API: the RFP list - the active company's RFPs, or every company the analyst can see under "All companies" (rfpListSql). Most recently updated first.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_rfps.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET search` |
| Input | search  optional text to match against the RFP name |
| Output | {success, rfps: [...]} |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 |  | read [tenants](/code/tables/tenants.md) |  |
| 35 |  | read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md), read [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md), read [rfps](/code/tables/rfps.md), read [analysts](/code/tables/analysts.md), read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpListSql()](/code/functions/rfpListSql.md), [rfpTenantReady()](/code/functions/rfpTenantReady.md)

