# apiV1PermissionCatalog()

FreeITSM REST API v1 — permission catalog.

The single source of truth for every permission an API key can carry, used
by three consumers: the v1 auth layer (enforcement), the System > API admin
page (the checkbox matrix when creating/editing a key), and the docs page
(each endpoint states which permission it needs).

A key's permissions are stored on api_keys.permissions as JSON:
  { "tickets": ["read","create"], "ticket_notes": ["read"] }
Anything not present is denied — keys start from zero.

```php
function apiV1PermissionCatalog(): array
```

Defined in [api/v1/lib/permissions.php](/code/files/api/v1/lib/permissions.php.md), line 15.

**Returns:** `array`

## Called from

- [api/system/api_keys/list_keys.php](/code/files/api/system/api_keys/list_keys.php.md)
- [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md) - in [apiV1BuildOpenApi()](/code/functions/apiV1BuildOpenApi.md)
- [api/v1/lib/permissions.php](/code/files/api/v1/lib/permissions.php.md) - in [apiV1NormalisePermissions()](/code/functions/apiV1NormalisePermissions.md)

