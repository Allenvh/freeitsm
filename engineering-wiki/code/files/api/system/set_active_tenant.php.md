# api/system/set_active_tenant.php

> API endpoint - 65 lines

API Endpoint: set the analyst's active company (tenant) context.

Stores the chosen tenant id in the session so the rest of the app can scope
to it. Validates that the analyst is actually allowed to access that company.

Requires a WRITABLE session — note the plain `session_start()` (NOT
read_and_close), because we write `$_SESSION`['active_tenant_id'].

## The endpoint

| | |
|---|---|
| URL | `api/system/set_active_tenant.php` |
| Protected by | Signed-in analyst (session check) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [setActiveTenantAll()](/code/functions/setActiveTenantAll.md), [setActiveTenantId()](/code/functions/setActiveTenantId.md)

