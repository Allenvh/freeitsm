# api/rfp-builder/save_rfp.php

> API endpoint - 117 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Contracts](https://github.com/edmozley/freeitsm/wiki/Contracts)

API: create or update an RFP - name, status, style guide, contract and chosen supplier.

On a multi-company install the RFP's company must be one the analyst can reach, and a
linked contract must be visible to them and in the same company - refused, never
quietly replaced.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/save_rfp.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Input | id                  the RFP to update - omit to create one |
| Input | name                required |
| Input | status              draft, collecting, consolidating, generating, scoring, closed or abandoned |
| Input | style_guide         optional writing guidance for the AI-generated sections |
| Input | contract_id         optional contract the RFP leads to |
| Input | chosen_supplier_id  optional winning supplier |
| Input | tenant_id           optional company (multi-company installs) |
| Output | {success, id} |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 88 |  | **update** [rfps](/code/tables/rfps.md) (`name`, `status`, `style_guide`, `contract_id`, `chosen_supplier_id`, `updated_datetime`) |  |
| 99 |  | **update** [rfps](/code/tables/rfps.md) (`tenant_id`) |  |
| 101 |  | **insert** [rfps](/code/tables/rfps.md) (`name`, `status`, `style_guide`, `contract_id`, `chosen_supplier_id`, `created_by_analyst_id`) |  |
| 110 |  | **update** [rfps](/code/tables/rfps.md) (`tenant_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [contractCanView()](/code/functions/contractCanView.md), [contractTenantOf()](/code/functions/contractTenantOf.md), [contractTenantReady()](/code/functions/contractTenantReady.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md), [rfpTenantOf()](/code/functions/rfpTenantOf.md), [rfpTenantReady()](/code/functions/rfpTenantReady.md)

