# d005IsPublicByDesign()

Endpoints that are PUBLIC BY DESIGN — they must work before anyone is logged in, or they belong to the self-service portal, which authenticates its own way. Being unguarded is the point, so they are excluded from the findings rather than shouted about. Listed explicitly so the exclusion is a decision, not an accident.

```php
function d005IsPublicByDesign(string $rel): bool
```

Defined in [api/system/debug-tools/D005_endpoint_permissions.php](/code/files/api/system/debug-tools/D005_endpoint_permissions.php.md), line 74.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rel` | `string` |  | the endpoint's path from the app root, with forward slashes, e.g. api/auth/login.php |

**Returns:** `bool`

## Called from

- [api/system/debug-tools/D005_endpoint_permissions.php](/code/files/api/system/debug-tools/D005_endpoint_permissions.php.md)

