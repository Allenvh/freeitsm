# api/domains/list.php

> API endpoint - 20 lines

GET — the domain register, scoped to the analyst's company (or every company they can see, in the "All companies" view). Optional filters: q, status_id, purpose, owner_analyst_id, registrar_supplier_id, tag, expiring_days. Also returns the lookup lists the register's filters and bulk editor use.

## The endpoint

| | |
|---|---|
| URL | `api/domains/list.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |
| Reads | `GET with_lookups` |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), [includes/domains/read.php](/code/files/includes/domains/read.php.md)

**Calls:** [domainApiLookups()](/code/functions/domainApiLookups.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainListRows()](/code/functions/domainListRows.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

