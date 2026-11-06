# includes/services/contracts.php

> PHP - 655 lines

ContractsService — the shared write rules for the contracts module's core entities: contracts, suppliers and contract-term values.

Shared by the UI endpoints (api/contracts/*.php) and the REST API
(api/v1/resources/contracts.php). Each caller passes an ActorContext +
canonical input; this layer validates + writes and returns the affected id(s)
or throws ServiceError. It never emits HTTP.

SCOPE: this covers the cleanly-duplicated overlaps only. The module's lookup
SETTINGS (contract/supplier statuses, supplier types, payment schedules,
term tabs) are UI-only (the API exposes them read-only) and stay on their own
endpoints; supplier CONTACTS are modelled flat by the UI (supplier_id in the
body, movable) but nested under a supplier by the API — a structural mismatch
that isn't worth forcing into one shape — so they stay separate too.

Canonical behaviour = the API resource's: required contract_number + title /
supplier legal_name, lookup ids validated (422), dates validated (422),
contract_value numeric + currency a 3-letter code, a duplicate
contract_number is a 409, and created timestamps are stamped UTC (the raw UI
relied on the server-local column default).

## Classes

- [ContractsService](/code/classes/ContractsService.md) (class, line 30)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 | `ContractsService::saveContract` | **update** [contracts](/code/tables/contracts.md) (`contract_number`, `title`, `description`, `supplier_id`, `contract_owner_id`, `contract_status_id`, `contract_start`, `contract_end`, `notice_period_days`, `notice_date`, `contract_value`, `currency`) |  |
| 85 | `ContractsService::saveContract` | **insert** [contracts](/code/tables/contracts.md) (`contract_number`, `title`, `description`, `supplier_id`, `contract_owner_id`, `contract_status_id`, `contract_start`, `contract_end`, `notice_period_days`, `notice_date`, `contract_value`, `currency`) |  |
| 111 | `ContractsService::deleteContract` | **delete** [contract_term_values](/code/tables/contract_term_values.md) |  |
| 117 | `ContractsService::deleteContract` | **delete** [contracts](/code/tables/contracts.md) |  |
| 151 | `ContractsService::saveTerms` | read [contract_term_values](/code/tables/contract_term_values.md) |  |
| 155 | `ContractsService::saveTerms` | **update** [contract_term_values](/code/tables/contract_term_values.md) (`content`, `updated_datetime`) |  |
| 158 | `ContractsService::saveTerms` | **insert** [contract_term_values](/code/tables/contract_term_values.md) (`contract_id`, `term_tab_id`, `content`, `created_datetime`) |  |
| 212 | `ContractsService::saveSupplier` | **update** [suppliers](/code/tables/suppliers.md) (`legal_name`, `trading_name`, `reg_number`, `vat_number`, `supplier_type_id`, `supplier_status_id`, `address_line_1`, `address_line_2`, `city`, `county`, `postcode`, `country`) |  |
| 222 | `ContractsService::saveSupplier` | **insert** [suppliers](/code/tables/suppliers.md) (`legal_name`, `trading_name`, `reg_number`, `vat_number`, `supplier_type_id`, `supplier_status_id`, `address_line_1`, `address_line_2`, `city`, `county`, `postcode`, `country`) |  |
| 243 | `ContractsService::deleteSupplier` | **update** [contacts](/code/tables/contacts.md) (`supplier_id`) |  |
| 244 | `ContractsService::deleteSupplier` | **update** [contracts](/code/tables/contracts.md) (`supplier_id`) |  |
| 246 | `ContractsService::deleteSupplier` | **update** [assets](/code/tables/assets.md) (`supplier_id`) |  |
| 247 | `ContractsService::deleteSupplier` | **update** [rfps](/code/tables/rfps.md) (`chosen_supplier_id`) |  |
| 248 | `ContractsService::deleteSupplier` | **delete** [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) |  |
| 249 | `ContractsService::deleteSupplier` | **delete** [rfp_scores](/code/tables/rfp_scores.md) |  |
| 255 | `ContractsService::deleteSupplier` | **delete** [suppliers](/code/tables/suppliers.md) |  |
| 321 | `ContractsService::assertNumberFree` | read [contracts](/code/tables/contracts.md) |  |
| 324 | `ContractsService::assertNumberFree` | read [contracts](/code/tables/contracts.md) |  |
| 347 | `ContractsService::assertMovable` | read [contract_assets](/code/tables/contract_assets.md), read [assets](/code/tables/assets.md) |  |
| 348 | `ContractsService::assertMovable` | read [project_contracts](/code/tables/project_contracts.md), read [projects](/code/tables/projects.md) |  |
| 349 | `ContractsService::assertMovable` | read [rfps](/code/tables/rfps.md) |  |
| 400 | `ContractsService::costCentreFor` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 418 | `ContractsService::writeCostCentre` | **update** [contracts](/code/tables/contracts.md) (`cost_centre_id`) |  |
| 430 | `ContractsService::writeTenant` | **update** [contracts](/code/tables/contracts.md) (`tenant_id`) |  |
| 443 | `ContractsService::assertVisible` | read [contracts](/code/tables/contracts.md) |  |
| 488 | `ContractsService::writeParty` | **update** [contracts](/code/tables/contracts.md) (`party_type`, `customer_tenant_id`, `customer_user_id`) |  |
| 630 | `ContractsService::resolveAnalyst` | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md), [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md)

**Included by:** [api/contracts/delete_contract.php](/code/files/api/contracts/delete_contract.php.md), [api/contracts/delete_supplier.php](/code/files/api/contracts/delete_supplier.php.md), [api/contracts/save_contract.php](/code/files/api/contracts/save_contract.php.md), [api/contracts/save_contract_terms.php](/code/files/api/contracts/save_contract_terms.php.md), [api/contracts/save_supplier.php](/code/files/api/contracts/save_supplier.php.md), [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md)

**Calls:** [contractNormaliseParty()](/code/functions/contractNormaliseParty.md), [contractPartyReady()](/code/functions/contractPartyReady.md), [contractStoreTenant()](/code/functions/contractStoreTenant.md), [contractTenantReady()](/code/functions/contractTenantReady.md), [contractVisibilitySqlForScope()](/code/functions/contractVisibilitySqlForScope.md), [costCentreByCode()](/code/functions/costCentreByCode.md), [costCentreCheck()](/code/functions/costCentreCheck.md), [costCentreReady()](/code/functions/costCentreReady.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

