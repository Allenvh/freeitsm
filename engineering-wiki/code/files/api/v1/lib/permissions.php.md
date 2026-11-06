# api/v1/lib/permissions.php

> API endpoint - 445 lines

FreeITSM REST API v1 — permission catalog.

The single source of truth for every permission an API key can carry, used
by three consumers: the v1 auth layer (enforcement), the System > API admin
page (the checkbox matrix when creating/editing a key), and the docs page
(each endpoint states which permission it needs).

A key's permissions are stored on api_keys.permissions as JSON:
  { "tickets": ["read","create"], "ticket_notes": ["read"] }
Anything not present is denied — keys start from zero.

## The endpoint

| | |
|---|---|
| URL | `api/v1/lib/permissions.php` |
| Protected by | REST API key |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiV1PermissionCatalog()](/code/functions/apiV1PermissionCatalog.md) | 15 | FreeITSM REST API v1 — permission catalog. |
| [apiV1NormalisePermissions()](/code/functions/apiV1NormalisePermissions.md) | 425 | Validate + normalise a raw permissions structure (from the admin UI or a stored JSON blob) against the catalog. Unknown resources/actions are dropped; the result is always ['resource' => ['action', ...]]. |

## How it hangs together

**Included by:** [api/mcp/index.php](/code/files/api/mcp/index.php.md), [api/system/api_keys/create_key.php](/code/files/api/system/api_keys/create_key.php.md), [api/system/api_keys/list_keys.php](/code/files/api/system/api_keys/list_keys.php.md), [api/system/api_keys/update_key.php](/code/files/api/system/api_keys/update_key.php.md), [api/v1/lib/bootstrap.php](/code/files/api/v1/lib/bootstrap.php.md), [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md)

