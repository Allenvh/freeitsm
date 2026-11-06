# api/domains/bulk_add.php

> API endpoint - 86 lines

POST {text, defaults:{status_id,purpose,owner_analyst_id,tags,…}, company_id?} — add many domains from a pasted list. POST {rows:[{domain_name, …fields}], company_id?} — add many from a CSV the browser has already split into rows (the column mapping happens on screen, where the person can see it).

Nothing is looked up here: a few hundred registry lookups do not fit in one
request. The screen then calls process.php in small batches and shows the
progress, and anything it does not finish the scheduled run picks up
(a never-looked-up domain is first in its queue).

## The endpoint

| | |
|---|---|
| URL | `api/domains/bulk_add.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainImportResolveNames()](/code/functions/domainImportResolveNames.md) | 57 | CSV columns carry names; the service wants ids. status / owner / registrar are matched by name (case-insensitive); an unmatched name is dropped rather than failing the row — the domain is still worth having. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 | `domainImportResolveNames` | read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 61 | `domainImportResolveNames` | read [analysts](/code/tables/analysts.md) |  |
| 62 | `domainImportResolveNames` | read [suppliers](/code/tables/suppliers.md) |  |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md)

**Calls:** [domainApiBody()](/code/functions/domainApiBody.md), [domainApiFail()](/code/functions/domainApiFail.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainApiTenantForCreate()](/code/functions/domainApiTenantForCreate.md), [domainNormalise()](/code/functions/domainNormalise.md)

