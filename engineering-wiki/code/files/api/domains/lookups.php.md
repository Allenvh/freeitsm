# api/domains/lookups.php

> API endpoint - 13 lines

GET — the lists the Domains forms draw from: statuses, analysts, registrars, registrar accounts (scoped), purposes, renewal modes, companies, and — for analysts who can open Contracts — contracts and contacts.

## The endpoint

| | |
|---|---|
| URL | `api/domains/lookups.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), [includes/domains/read.php](/code/files/includes/domains/read.php.md)

**Calls:** [domainApiLookups()](/code/functions/domainApiLookups.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

