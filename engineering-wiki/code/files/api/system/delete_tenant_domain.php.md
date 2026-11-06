# api/system/delete_tenant_domain.php

> API endpoint - 37 lines

API: Remove an email domain from a company (tenant). POST JSON { id } (the tenant_domains row id)

Future mail from that domain will fall through to triage (shared intake) or
whichever other path applies. Nothing is deleted beyond the mapping.

## The endpoint

| | |
|---|---|
| URL | `api/system/delete_tenant_domain.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | **delete** [tenant_domains](/code/tables/tenant_domains.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

