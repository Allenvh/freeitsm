# api/rfp-builder/get_rfp.php

> API endpoint - 72 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Contracts](https://github.com/edmozley/freeitsm/wiki/Contracts)

API: one RFP with its contract, chosen supplier, creator and the counts behind each stage (documents, requirements, conflicts, categories, sections, invited suppliers).

Also returns its company, shown only when the install has more than one.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_rfp.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET id` |
| Input | id  the RFP |
| Output | {success, rfp} |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md), read [rfp_conflicts](/code/tables/rfp_conflicts.md), read [rfp_categories](/code/tables/rfp_categories.md), read [rfp_output_sections](/code/tables/rfp_output_sections.md), read [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md), read [rfps](/code/tables/rfps.md), read [analysts](/code/tables/analysts.md), read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getTenantById()](/code/functions/getTenantById.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md), [rfpTenantOf()](/code/functions/rfpTenantOf.md)

