# api/domains/delete.php

> API endpoint - 21 lines

POST {id} or {ids:[…]} — delete one or several domains, with their history, alert ledger, look-alikes and certificates. Each id is scope-checked by the service; one the analyst cannot reach reads as not found.

## The endpoint

| | |
|---|---|
| URL | `api/domains/delete.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md)

**Calls:** [domainApiBody()](/code/functions/domainApiBody.md), [domainApiFail()](/code/functions/domainApiFail.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md)

