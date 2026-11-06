# api/domains/people.php

> API endpoint - 35 lines

GET ?domain_id=&q= — who could be this domain's customer (#153): active users in the domain's own company, plus (#162) active suppliers and supplier contacts, matching q. A domain the analyst cannot see reads as not found, so the search never reaches into another company.

## The endpoint

| | |
|---|---|
| URL | `api/domains/people.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |
| Reads | `GET domain_id`, `GET q` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 16 |  | read [domains](/code/tables/domains.md) |  |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), [includes/domains/customer.php](/code/files/includes/domains/customer.php.md)

**Calls:** [analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [domainApiFail()](/code/functions/domainApiFail.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainCustomerSearch()](/code/functions/domainCustomerSearch.md), [domainCustomerSupplierSearch()](/code/functions/domainCustomerSupplierSearch.md), [domainPartiesReady()](/code/functions/domainPartiesReady.md)

