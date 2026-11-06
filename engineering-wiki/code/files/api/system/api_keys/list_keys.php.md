# api/system/api_keys/list_keys.php

> API endpoint - 81 lines

API Endpoint (internal, session): list REST API v1 keys + everything the System > API page needs to render its create/edit form (permission catalog, analysts, companies).

## The endpoint

| | |
|---|---|
| URL | `api/system/api_keys/list_keys.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [api_keys](/code/tables/api_keys.md), read [analysts](/code/tables/analysts.md) |  |
| 59 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [api/v1/lib/permissions.php](/code/files/api/v1/lib/permissions.php.md)

**Calls:** [apiV1NormalisePermissions()](/code/functions/apiV1NormalisePermissions.md), [apiV1PermissionCatalog()](/code/functions/apiV1PermissionCatalog.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getAllTenants()](/code/functions/getAllTenants.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

