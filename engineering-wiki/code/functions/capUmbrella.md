# capUmbrella()

The module's umbrella capability ('<module>.manage'), or null if it has none.

```php
function capUmbrella(string $module): ?string
```

Defined in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 508.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$module` | `string` |  | a Layer 1 module slug, e.g. 'assets' |

**Returns:** `?string`

## Calls

[capRegistry()](/code/functions/capRegistry.md)

## Called from

- [includes/capabilities.php](/code/files/includes/capabilities.php.md) - in [capGroups()](/code/functions/capGroups.md)

