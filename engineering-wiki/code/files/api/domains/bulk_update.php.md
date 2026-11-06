# api/domains/bulk_update.php

> API endpoint - 16 lines

POST {ids:[…], fields:{status_id?, purpose?, owner_analyst_id?, renewal_mode?, registrar_supplier_id?, registrar_account_id?, monitoring_enabled?, cost_centre?, tags?}} — set the same fields on many domains. Each goes through the service one at a time, with its own history rows.

## The endpoint

| | |
|---|---|
| URL | `api/domains/bulk_update.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md)

**Calls:** [domainApiBody()](/code/functions/domainApiBody.md), [domainApiFail()](/code/functions/domainApiFail.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md)

