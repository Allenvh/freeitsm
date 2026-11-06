# api/domains/accounts.php

> API endpoint - 47 lines

Registrar accounts — the logins at registrars that hold the domains.

GET              the accounts in the analyst's company (or all they can see),
                 with how many domains each holds
POST save        {id?, supplier_id, account_name, account_reference, login_url,
                  owner_analyst_id, two_factor_holder, notes, company_id?}
POST delete      {id} — its domains are unlinked, never deleted

Operational data, not settings: anyone in the module maintains them, the way
anyone in Contracts maintains suppliers. No password is ever stored.

## The endpoint

| | |
|---|---|
| URL | `api/domains/accounts.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [domains](/code/tables/domains.md), read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md), read [suppliers](/code/tables/suppliers.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md)

**Calls:** [activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [domainApiBody()](/code/functions/domainApiBody.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainApiTenantForCreate()](/code/functions/domainApiTenantForCreate.md)

