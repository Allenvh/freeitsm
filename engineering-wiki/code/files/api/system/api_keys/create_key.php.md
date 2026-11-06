# api/system/api_keys/create_key.php

> API endpoint - 109 lines

API Endpoint (internal, session): create a REST API v1 key.

The full key (fitsm_...) is generated here, returned ONCE in this response,
and only its SHA-256 hash is stored — it can never be shown again.

## The endpoint

| | |
|---|---|
| URL | `api/system/api_keys/create_key.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 69 |  | read [analysts](/code/tables/analysts.md) |  |
| 83 |  | **insert** [api_keys](/code/tables/api_keys.md) (`name`, `key_prefix`, `key_hash`, `analyst_id`, `permissions`, `company_ids`, `rate_limit_per_minute`, `active`, `expires_at`, `created_by`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [api/v1/lib/permissions.php](/code/files/api/v1/lib/permissions.php.md), [api/v1/lib/response.php](/code/files/api/v1/lib/response.php.md), [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md)

**Calls:** [apiKeyCheckCompanies()](/code/functions/apiKeyCheckCompanies.md), [apiV1NormalisePermissions()](/code/functions/apiV1NormalisePermissions.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

