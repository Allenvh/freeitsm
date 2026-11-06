# api/system/api_keys/update_key.php

> API endpoint - 124 lines

API Endpoint (internal, session): update a REST API v1 key — rename, enable/disable, re-scope companies, change permissions, expiry or rate limit. The key material itself is immutable (revoke + create a new key).

## The endpoint

| | |
|---|---|
| URL | `api/system/api_keys/update_key.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [api_keys](/code/tables/api_keys.md) |  |
| 54 |  | read [analysts](/code/tables/analysts.md) |  |
| 84 |  | read [api_keys](/code/tables/api_keys.md) |  |
| 117 |  | **update** [api_keys](/code/tables/api_keys.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [api/v1/lib/permissions.php](/code/files/api/v1/lib/permissions.php.md), [api/v1/lib/response.php](/code/files/api/v1/lib/response.php.md), [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md)

**Calls:** [apiKeyCheckCompanies()](/code/functions/apiKeyCheckCompanies.md), [apiV1NormalisePermissions()](/code/functions/apiV1NormalisePermissions.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

