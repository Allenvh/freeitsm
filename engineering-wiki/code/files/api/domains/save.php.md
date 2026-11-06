# api/domains/save.php

> API endpoint - 39 lines

POST — create (no id) or update (id) a domain. Thin adapter over DomainsService::saveDomain().

A NEW domain is looked up at its registry and checked straight away (unless
lookups are switched off), so the person who added it sees its dates and
grade on the page they land on — a second or two, once.

An auth_code in the body needs Cap::DOMAINS_AUTH_CODES; without it the key
is refused rather than silently ignored.

## The endpoint

| | |
|---|---|
| URL | `api/domains/save.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md)

**Calls:** [analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [domainApiBody()](/code/functions/domainApiBody.md), [domainApiFail()](/code/functions/domainApiFail.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainApiTenantForCreate()](/code/functions/domainApiTenantForCreate.md), [domainHasCap()](/code/functions/domainHasCap.md), [domainSetting()](/code/functions/domainSetting.md)

