# api/v1/resources/contracts.php

> API endpoint - 615 lines

FreeITSM REST API v1 — contracts resource (contracts, suppliers, contacts, contract terms). The RFP Builder is deliberately NOT exposed (internal-only by design).

Mirrors the module's internal endpoints:
  - contract create/update mirror save_contract.php (contract_number +
    title mandatory, everything else nullable, is_active default 1);
  - supplier create/update mirror save_supplier.php — plus the API can set
    `supplies_assets` (the UI never writes it; it's the flag the Assets
    module filters suppliers by, so integrations need it);
  - contract terms mirror save_contract_terms.php's per-tab upsert;
  - supplier delete mirrors delete_supplier.php's explicit nullify-then-
    delete; contract delete FIXES the internal endpoint's orphan bug by
    removing contract_term_values explicitly (the contracts domain
    historically had no foreign keys at all — see changelog #691).

Deliberate API improvements, documented: duplicate contract_number on
create → 409 (no DB unique exists and the UI allows dupes — machines
shouldn't create them); lookup ids validated with friendly 422s; renewal
filters mirror the Watchtower/dashboard SQL shapes (expiring_within_days,
notice_within_days, expired).

Every contract belongs to a COMPANY (3.5.0) - `company` in the payload, NULL
stored as the Default company. A key sees only contracts in companies its
scope reaches (one not reached is a 404, never a 403, so ids cannot be
probed); a new contract lands in `company_id` (checked against the key) or the
key's default company; PATCH with `company_id` moves one, refused while it has
assets, projects or RFPs in its old company. Contract numbers are unique per
company. Who it is WITH is `party_type` / `customer`, set independently. No
audit trail exists in the product and none is invented here.

Contract / supplier / term WRITES are delegated to ContractsService
(includes/services/contracts.php); the read handlers + serializers, the
supplier CONTACTS endpoints, and the read-only lookup lists stay here.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/contracts.php` |
| Protected by | REST API key |
| Reads | `GET is_active`, `GET party_type`, `GET company_id`, `GET q`, `GET expiring_within_days`, `GET expired`, `GET notice_within_days`, `GET sort` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiContractSelect()](/code/functions/apiContractSelect.md) | 54 | The SELECT for contracts, with supplier, owner, status and payment schedule names, the customer and company columns and, once Database Verification has added it, the cost centre. |
| [apiContractVisibility()](/code/functions/apiContractVisibility.md) | 80 | The key's view of customer contracts (#153): " AND (...)" + its args. |
| [apiSerializeContract()](/code/functions/apiSerializeContract.md) | 90 | Shapes a contract row for the API - number, title, company, supplier or customer (company and person), owner, status, dates, value and the rest of the record. |
| [apiLoadContract()](/code/functions/apiLoadContract.md) | 144 | Loads one contract row the key may see (apiContractVisibility), or answers 404 and stops. |
| [apiSupplierSelect()](/code/functions/apiSupplierSelect.md) | 156 | The SELECT for suppliers, with type and status names and their numbers of contacts and contracts. |
| [apiSerializeSupplier()](/code/functions/apiSerializeSupplier.md) | 173 | Shapes a supplier row for the API - legal, trading and display name, registration numbers, type, status, address and counts. |
| [apiLoadSupplier()](/code/functions/apiLoadSupplier.md) | 212 | Loads one supplier row (apiSupplierSelect), or answers 404 and stops. |
| [apiContractsList()](/code/functions/apiContractsList.md) | 225 | GET /contracts |
| [apiContractsGet()](/code/functions/apiContractsGet.md) | 339 | GET /contracts/{id} - one contract. |
| [apiContractCompany()](/code/functions/apiContractCompany.md) | 353 | `company_id` from a request body as the service's tenant_id: checked to exist and to be in the key's reach (403), or - on a create with none - the key's default company. On an update with none, nothing: the contract stays put. |
| [apiContractsCreate()](/code/functions/apiContractsCreate.md) | 371 | POST /contracts - creates a contract through ContractsService in the company named (or the key's default) and returns it. |
| [apiContractsUpdate()](/code/functions/apiContractsUpdate.md) | 382 | PATCH /contracts/{id} |
| [apiContractsDelete()](/code/functions/apiContractsDelete.md) | 393 | DELETE /contracts/{id} — explicit children (the contracts domain had no FKs) |
| [apiContractTermsGet()](/code/functions/apiContractTermsGet.md) | 403 | Contract terms — GET + bulk upsert (mirrors save_contract_terms.php) |
| [apiContractTermsSave()](/code/functions/apiContractTermsSave.md) | 426 | POST /contracts/{id}/terms - saves the contract's terms through ContractsService and returns them. |
| [apiSuppliersGet()](/code/functions/apiSuppliersGet.md) | 436 | Suppliers — full CRUD (GET /suppliers stays the lite reference list) |
| [apiSuppliersCreate()](/code/functions/apiSuppliersCreate.md) | 448 | POST /suppliers - creates a supplier through ContractsService and returns it. |
| [apiSuppliersUpdate()](/code/functions/apiSuppliersUpdate.md) | 456 | PATCH /suppliers/{id} - updates a supplier through ContractsService and returns it. |
| [apiSuppliersDelete()](/code/functions/apiSuppliersDelete.md) | 464 | DELETE /suppliers/{id} — mirrors delete_supplier.php's explicit nullify-then-delete. |
| [apiSerializeContact()](/code/functions/apiSerializeContact.md) | 475 | Supplier contacts |
| [apiSupplierContactsList()](/code/functions/apiSupplierContactsList.md) | 490 | GET /suppliers/{id}/contacts - the supplier's contacts, by surname. |
| [apiSupplierContactsCreate()](/code/functions/apiSupplierContactsCreate.md) | 504 | POST /suppliers/{id}/contacts - adds a contact to the supplier. First name and surname are required. |
| [apiSupplierContactsUpdate()](/code/functions/apiSupplierContactsUpdate.md) | 533 | PATCH /suppliers/{id}/contacts/{contactId} - updates the fields sent, keeping the rest. First name and surname cannot be emptied. |
| [apiSupplierContactsDelete()](/code/functions/apiSupplierContactsDelete.md) | 566 | DELETE /suppliers/{id}/contacts/{contactId} - deletes one of the supplier's contacts. |
| [apiContractLookupList()](/code/functions/apiContractLookupList.md) | 581 | Reference lookups |
| [apiContractStatusesList()](/code/functions/apiContractStatusesList.md) | 596 | GET /contract-statuses - the contract statuses. |
| [apiPaymentSchedulesList()](/code/functions/apiPaymentSchedulesList.md) | 600 | GET /payment-schedules - the payment schedules. |
| [apiSupplierTypesList()](/code/functions/apiSupplierTypesList.md) | 604 | GET /supplier-types - the supplier types. |
| [apiSupplierStatusesList()](/code/functions/apiSupplierStatusesList.md) | 608 | GET /supplier-statuses - the supplier statuses. |
| [apiContractTermTabsList()](/code/functions/apiContractTermTabsList.md) | 612 | GET /contract-term-tabs - the contract term tabs. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 | `apiContractSelect` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 65 | `apiContractSelect` | read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [analysts](/code/tables/analysts.md), read [contract_statuses](/code/tables/contract_statuses.md), read [payment_schedules](/code/tables/payment_schedules.md) |  |
| 157 | `apiSupplierSelect` | read [contacts](/code/tables/contacts.md), read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [supplier_types](/code/tables/supplier_types.md), read [supplier_statuses](/code/tables/supplier_statuses.md) |  |
| 324 | `apiContractsList` | read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md) |  |
| 406 | `apiContractTermsGet` | read [contract_term_tabs](/code/tables/contract_term_tabs.md), read [contract_term_values](/code/tables/contract_term_values.md) |  |
| 439 | `apiSuppliersGet` | read [contacts](/code/tables/contacts.md) |  |
| 493 | `apiSupplierContactsList` | read [contacts](/code/tables/contacts.md) |  |
| 516 | `apiSupplierContactsCreate` | **insert** [contacts](/code/tables/contacts.md) (`supplier_id`, `first_name`, `surname`, `email`, `mobile`, `job_title`, `direct_dial`, `switchboard`, `is_active`, `created_datetime`) |  |
| 524 | `apiSupplierContactsCreate` | read [contacts](/code/tables/contacts.md) |  |
| 536 | `apiSupplierContactsUpdate` | read [contacts](/code/tables/contacts.md) |  |
| 555 | `apiSupplierContactsUpdate` | **update** [contacts](/code/tables/contacts.md) (`first_name`, `surname`, `email`, `mobile`, `job_title`, `direct_dial`, `switchboard`, `is_active`) |  |
| 560 | `apiSupplierContactsUpdate` | read [contacts](/code/tables/contacts.md) |  |
| 569 | `apiSupplierContactsDelete` | **delete** [contacts](/code/tables/contacts.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/contracts.php](/code/files/includes/services/contracts.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md), [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiKeyDefaultTenantId()](/code/functions/apiKeyDefaultTenantId.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseDateOnly()](/code/functions/apiParseDateOnly.md), [apiRespond()](/code/functions/apiRespond.md), [contractOwnerSql()](/code/functions/contractOwnerSql.md), [contractPartyReady()](/code/functions/contractPartyReady.md), [contractPartySql()](/code/functions/contractPartySql.md), [contractTenantReady()](/code/functions/contractTenantReady.md), [contractVisibilitySqlForScope()](/code/functions/contractVisibilitySqlForScope.md), [costCentreReady()](/code/functions/costCentreReady.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [naive_today_sql()](/code/functions/naive_today_sql.md)

