# api/domains/dashboard.php

> API endpoint - 106 lines

GET — the numbers behind Domains → Dashboard, over the same scoped register the list shows (so the dashboard can never count a domain the list hides): totals, the attention list, grades, email-security posture, the next twelve months of renewals by month with their cost, and spend by registrar / company.

## The endpoint

| | |
|---|---|
| URL | `api/domains/dashboard.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [domains](/code/tables/domains.md) |  |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), [includes/domains/read.php](/code/files/includes/domains/read.php.md)

**Calls:** [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainListRows()](/code/functions/domainListRows.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

