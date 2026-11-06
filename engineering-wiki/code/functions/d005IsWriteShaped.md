# d005IsWriteShaped()

Does this endpoint change anything? Writes are what make a missing guard dangerous.

```php
function d005IsWriteShaped(string $src): bool
```

Defined in [api/system/debug-tools/D005_endpoint_permissions.php](/code/files/api/system/debug-tools/D005_endpoint_permissions.php.md), line 118.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$src` | `string` |  | the endpoint file's full PHP source |

**Returns:** `bool`

## Called from

- [api/system/debug-tools/D005_endpoint_permissions.php](/code/files/api/system/debug-tools/D005_endpoint_permissions.php.md)

