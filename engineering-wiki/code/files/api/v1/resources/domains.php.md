# api/v1/resources/domains.php

> API endpoint - 442 lines

FreeITSM REST API v1 — domains resource (#154).

Every WRITE goes through DomainsService (includes/services/domains.php), the
same code the Domains screens use, so a domain added or changed through the
API gets the same validation, the same history rows and the same workflow
events as one changed by a person. The reads + serialiser live here: the API
shape is a public contract, not the screens' shape.

COMPANY SCOPE: domains are scoped data. The list is filtered to the key's
companies (apiKeyTenantFilter), every by-id route 404s outside them
(apiKeyCanAccessTenantRow — never 403, so ids cannot be probed), and a new
domain lands in `company_id` (checked against the key) or the key's default
company. Registrar accounts follow the same rule.

🔴 AUTH CODES NEVER CROSS THE API, in either direction. The serialiser says
only whether one is set; a body containing `auth_code` is refused. A
transfer secret belongs behind the analyst capability and its audit trail,
not behind a key that may be sitting in a monitoring tool's config file.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/domains.php` |
| Protected by | REST API key |
| Reads | `GET purpose`, `GET domain_name`, `GET q`, `GET tag`, `GET expiring_within_days`, `GET expired`, `GET transfer_lock`, `GET grade`, `GET sort`, `GET company_id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiDomainSelect()](/code/functions/apiDomainSelect.md) | 34 | The SELECT for domains, with status, registrar, account, owner, technical contact and company names and, once Database Verification has added them, the customer columns. |
| [apiSerializeDomain()](/code/functions/apiSerializeDomain.md) | 71 | Shapes a domain row for the API - names, company, status, registrar and account, dates with days to expiry, and the rest of the record. The auth code itself is never included - only auth_code_set. |
| [apiLoadDomain()](/code/functions/apiLoadDomain.md) | 147 | The by-id gate: existence + company scope, both failing as 404. |
| [apiDomainRefuseAuthCode()](/code/functions/apiDomainRefuseAuthCode.md) | 161 | Answers 422 and stops when a request body carries auth_code - it can only be read or set on the Domains screen, where it is permission-controlled and audited. |
| [apiDomainsList()](/code/functions/apiDomainsList.md) | 170 | GET /domains |
| [apiDomainsGet()](/code/functions/apiDomainsGet.md) | 255 | GET /domains/{id} |
| [apiDomainsCreate()](/code/functions/apiDomainsCreate.md) | 262 | POST /domains |
| [apiDomainsUpdate()](/code/functions/apiDomainsUpdate.md) | 288 | PATCH /domains/{id} |
| [apiDomainsDelete()](/code/functions/apiDomainsDelete.md) | 304 | DELETE /domains/{id} |
| [apiDomainsLookup()](/code/functions/apiDomainsLookup.md) | 317 | POST /domains/{id}/lookup — ask the registry now |
| [apiDomainsCheck()](/code/functions/apiDomainsCheck.md) | 334 | POST /domains/{id}/check — run the health and security checks now |
| [apiDomainsFindings()](/code/functions/apiDomainsFindings.md) | 351 | GET /domains/{id}/findings — the last check's findings, with English text |
| [apiDomainFindings()](/code/functions/apiDomainFindings.md) | 363 | Findings with their English title and advice filled in — a machine consumer has no lang file. |
| [apiDomainsHistory()](/code/functions/apiDomainsHistory.md) | 386 | GET /domains/{id}/history |
| [apiDomainStatusesList()](/code/functions/apiDomainStatusesList.md) | 408 | GET /domain-statuses · GET /domain-registrar-accounts |
| [apiDomainAccountsList()](/code/functions/apiDomainAccountsList.md) | 420 | GET /domain-registrar-accounts - the registrar accounts in the key's companies, with registrar, owner, company and how many domains each holds. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `apiDomainSelect` | read [users](/code/tables/users.md) |  |
| 44 | `apiDomainSelect` | read [suppliers](/code/tables/suppliers.md), read [contacts](/code/tables/contacts.md), read [analysts](/code/tables/analysts.md) |  |
| 48 | `apiDomainSelect` | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md), read [suppliers](/code/tables/suppliers.md), read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md), read [analysts](/code/tables/analysts.md), read [contacts](/code/tables/contacts.md), read [tenants](/code/tables/tenants.md) |  |
| 241 | `apiDomainsList` | read [domains](/code/tables/domains.md) |  |
| 390 | `apiDomainsHistory` | read [domain_audit](/code/tables/domain_audit.md) |  |
| 393 | `apiDomainsHistory` | read [domain_audit](/code/tables/domain_audit.md), read [analysts](/code/tables/analysts.md) |  |
| 409 | `apiDomainStatusesList` | read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 423 | `apiDomainAccountsList` | read [domains](/code/tables/domains.md), read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md), read [suppliers](/code/tables/suppliers.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/domains.php](/code/files/includes/services/domains.php.md), [includes/domains/customer.php](/code/files/includes/domains/customer.php.md), [lang/en/domains.php](/code/files/lang/en/domains.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md), [apiKeyDefaultTenantId()](/code/functions/apiKeyDefaultTenantId.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiPagination()](/code/functions/apiPagination.md), [apiRespond()](/code/functions/apiRespond.md), [domainCustomerReady()](/code/functions/domainCustomerReady.md), [domainNormalise()](/code/functions/domainNormalise.md), [domainPartiesReady()](/code/functions/domainPartiesReady.md), [domainPurposes()](/code/functions/domainPurposes.md), [domainSetting()](/code/functions/domainSetting.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md)

