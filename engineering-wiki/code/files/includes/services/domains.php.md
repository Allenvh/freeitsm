# includes/services/domains.php

> PHP - 1081 lines

DomainsService — the write rules for the Domains module (#154), ONCE.

Shared by the UI endpoints (api/domains/*.php), the REST API
(api/v1/resources/domains.php) and the scheduled run (cron/domains.php). Each
caller passes an ActorContext + canonical input; this layer validates, writes,
records history and fires events, and returns ids or throws ServiceError. It
never emits HTTP.

─────────────────────────────────────────────────────────────────────────────
COMPANIES

A domain is scoped data: it belongs to exactly one company, NULL = the
Default company. Every by-id method starts with `loadForActor()`, which 404s a
domain in a company the caller cannot reach — never 403, so a company's
domain list cannot be probed by guessing ids. The same name may be held by
two companies (an MSP can manage example.com for one client and example.net
for another, and nothing stops two clients sharing a name in their records),
so the duplicate check is per company.

─────────────────────────────────────────────────────────────────────────────
HISTORY

Every field that changes gets a domain_audit row, whoever changed it: a
person (analyst_id set), a registry lookup (source 'lookup'), a nightly check
(source 'check') or change detection (source 'monitor'). The auth code is the
exception that proves the rule: its CHANGE is recorded, its value never is.

## Classes

- [DomainsService](/code/classes/DomainsService.md) (class, line 43)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 137 | `DomainsService::createDomain` | read [domains](/code/tables/domains.md) |  |
| 157 | `DomainsService::createDomain` | read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 161 | `DomainsService::createDomain` | **insert** [domains](/code/tables/domains.md) |  |
| 166 | `DomainsService::createDomain` | **update** [domains](/code/tables/domains.md) (`auth_code`) |  |
| 198 | `DomainsService::updateDomain` | read [domains](/code/tables/domains.md) |  |
| 230 | `DomainsService::updateDomain` | **update** [domains](/code/tables/domains.md) |  |
| 261 | `DomainsService::deleteDomain` | **delete** [domains](/code/tables/domains.md) |  |
| 421 | `DomainsService::applyLookup` | **update** [domains](/code/tables/domains.md) |  |
| 438 | `DomainsService::recordLookupError` | **update** [domains](/code/tables/domains.md) (`last_lookup_datetime`, `last_lookup_error`) |  |
| 464 | `DomainsService::runChecks` | **update** [domains](/code/tables/domains.md) (`check_results`, `security_score`, `security_grade`, `ssl_expiry_date`, `ssl_issuer`, `watch_baseline`, `last_check_datetime`) |  |
| 553 | `DomainsService::saveStatus` | read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 558 | `DomainsService::saveStatus` | **update** [domain_statuses](/code/tables/domain_statuses.md) (`name`, `colour`, `alerts_enabled`, `is_active`, `display_order`) |  |
| 562 | `DomainsService::saveStatus` | **insert** [domain_statuses](/code/tables/domain_statuses.md) (`name`, `colour`, `alerts_enabled`, `is_active`, `display_order`, `created_datetime`) |  |
| 574 | `DomainsService::deleteStatus` | read [domains](/code/tables/domains.md) |  |
| 579 | `DomainsService::deleteStatus` | **delete** [domain_statuses](/code/tables/domain_statuses.md) |  |
| 608 | `DomainsService::saveRegistrarAccount` | **update** [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) (`supplier_id`, `account_name`, `account_reference`, `login_url`, `owner_analyst_id`, `two_factor_holder`, `notes`) |  |
| 616 | `DomainsService::saveRegistrarAccount` | **insert** [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) (`supplier_id`, `account_name`, `account_reference`, `login_url`, `owner_analyst_id`, `two_factor_holder`, `notes`, `tenant_id`, `created_by`, `created_datetime`) |  |
| 630 | `DomainsService::deleteRegistrarAccount` | **update** [domains](/code/tables/domains.md) (`registrar_account_id`) |  |
| 631 | `DomainsService::deleteRegistrarAccount` | **delete** [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) |  |
| 645 | `DomainsService::loadRow` | read [domains](/code/tables/domains.md) |  |
| 671 | `DomainsService::loadAccountForActor` | read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) |  |
| 730 | `DomainsService::validateField` | read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) |  |
| 850 | `DomainsService::normaliseParties` | read [contacts](/code/tables/contacts.md) |  |
| 865 | `DomainsService::normaliseParties` | read [contacts](/code/tables/contacts.md) |  |
| 899 | `DomainsService::requireAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 939 | `DomainsService::matchSupplier` | read [suppliers](/code/tables/suppliers.md) |  |
| 966 | `DomainsService::audit` | **insert** [domain_audit](/code/tables/domain_audit.md) (`domain_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `source`, `created_datetime`) |  |
| 990 | `DomainsService::assertContractVisible` | read [contracts](/code/tables/contracts.md) |  |
| 1012 | `DomainsService::auditDisplay` | read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 1013 | `DomainsService::auditDisplay` | read [suppliers](/code/tables/suppliers.md) |  |
| 1014 | `DomainsService::auditDisplay` | read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) |  |
| 1015 | `DomainsService::auditDisplay` | read [analysts](/code/tables/analysts.md) |  |
| 1016 | `DomainsService::auditDisplay` | read [contacts](/code/tables/contacts.md) |  |
| 1017 | `DomainsService::auditDisplay` | read [analysts](/code/tables/analysts.md) |  |
| 1018 | `DomainsService::auditDisplay` | read [suppliers](/code/tables/suppliers.md) |  |
| 1019 | `DomainsService::auditDisplay` | read [contacts](/code/tables/contacts.md) |  |
| 1020 | `DomainsService::auditDisplay` | read [contracts](/code/tables/contracts.md) |  |
| 1021 | `DomainsService::auditDisplay` | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/domains/settings.php](/code/files/includes/domains/settings.php.md), [includes/domains/names.php](/code/files/includes/domains/names.php.md), [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), [includes/domains/checks.php](/code/files/includes/domains/checks.php.md), [includes/domains/monitor.php](/code/files/includes/domains/monitor.php.md), [includes/domains/customer.php](/code/files/includes/domains/customer.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md)

**Included by:** [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md), [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md)

**Calls:** [contractTenantOf()](/code/functions/contractTenantOf.md), [contractTenantReady()](/code/functions/contractTenantReady.md), [contractVisibilitySqlForScope()](/code/functions/contractVisibilitySqlForScope.md), [decryptValue()](/code/functions/decryptValue.md), [documentsDetachParent()](/code/functions/documentsDetachParent.md), [domainAlertOnChange()](/code/functions/domainAlertOnChange.md), [domainBaseline()](/code/functions/domainBaseline.md), [domainBaselineDiff()](/code/functions/domainBaselineDiff.md), [domainBaselineMerge()](/code/functions/domainBaselineMerge.md), [domainCustomerPersonOk()](/code/functions/domainCustomerPersonOk.md), [domainCustomerReady()](/code/functions/domainCustomerReady.md), [domainLookup()](/code/functions/domainLookup.md), [domainNormalise()](/code/functions/domainNormalise.md), [domainParseSslHosts()](/code/functions/domainParseSslHosts.md), [domainPartiesReady()](/code/functions/domainPartiesReady.md), [domainPurposes()](/code/functions/domainPurposes.md), [domainRenewalModes()](/code/functions/domainRenewalModes.md), [domainRunChecks()](/code/functions/domainRunChecks.md), [domainSetting()](/code/functions/domainSetting.md), [domainSettings()](/code/functions/domainSettings.md), [domainSyncExpiryCalendar()](/code/functions/domainSyncExpiryCalendar.md), [encryptValue()](/code/functions/encryptValue.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

