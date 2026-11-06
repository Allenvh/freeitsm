# api/domains/get.php

> API endpoint - 45 lines

GET ?id= — one domain for its page: the record, its check findings, its history, its look-alikes and CT certificates, and whether the viewer may see the auth code. Out of the analyst's companies reads as not found.

## The endpoint

| | |
|---|---|
| URL | `api/domains/get.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 17 |  | read [domain_audit](/code/tables/domain_audit.md), read [analysts](/code/tables/analysts.md) |  |
| 24 |  | read [domain_lookalikes](/code/tables/domain_lookalikes.md) |  |
| 28 |  | read [domain_certificates](/code/tables/domain_certificates.md) |  |
| 32 |  | read [domain_audit](/code/tables/domain_audit.md) |  |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), [includes/domains/read.php](/code/files/includes/domains/read.php.md)

**Calls:** [analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [domainApiFail()](/code/functions/domainApiFail.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainDetail()](/code/functions/domainDetail.md), [domainHasCap()](/code/functions/domainHasCap.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

