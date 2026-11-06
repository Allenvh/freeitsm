# apiV1NormalisePermissions()

Validate + normalise a raw permissions structure (from the admin UI or a stored JSON blob) against the catalog. Unknown resources/actions are dropped; the result is always ['resource' => ['action', ...]].

```php
function apiV1NormalisePermissions($raw): array
```

Defined in [api/v1/lib/permissions.php](/code/files/api/v1/lib/permissions.php.md), line 425.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` |  |  | untrusted permissions map, ['resource' => ['action', ...]]; anything not an array gives [] |

**Returns:** `array`

## Calls

[apiV1PermissionCatalog()](/code/functions/apiV1PermissionCatalog.md)

## Called from

- [api/system/api_keys/create_key.php](/code/files/api/system/api_keys/create_key.php.md)
- [api/system/api_keys/list_keys.php](/code/files/api/system/api_keys/list_keys.php.md)
- [api/system/api_keys/update_key.php](/code/files/api/system/api_keys/update_key.php.md)
- [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) - in [apiAuthenticate()](/code/functions/apiAuthenticate.md)

