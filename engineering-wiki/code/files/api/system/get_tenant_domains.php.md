# api/system/get_tenant_domains.php

> API endpoint - 42 lines

API: List the email domains registered to a company (tenant). GET ?tenant_id=N — returns [{ id, domain }] for shared-intake routing.

"Company" is the user-facing word for a tenant; the table/code stays `tenant`.

## The endpoint

| | |
|---|---|
| URL | `api/system/get_tenant_domains.php` |
| Protected by | Module access: system (requireModuleAccessJson) |
| Reads | `GET tenant_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | read [tenant_domains](/code/tables/tenant_domains.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

