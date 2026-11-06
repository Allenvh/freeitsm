# capIsUmbrella()

Is this capability its module's umbrella?

```php
function capIsUmbrella(string $key): bool
```

Defined in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 655.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | a capability key; an unknown one is not an umbrella |

**Returns:** `bool`

## Calls

[capRegistry()](/code/functions/capRegistry.md)

## Called from

- [api/system/debug-tools/D005_endpoint_permissions.php](/code/files/api/system/debug-tools/D005_endpoint_permissions.php.md)

