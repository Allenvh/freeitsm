# capLabel()

Human description, shown in System -> Roles. Enum twin: `$cap`->label().

```php
function capLabel(string $key): string
```

Defined in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 454.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | a capability key; an unknown one is returned as its own label |

**Returns:** `string`

## Calls

[capRegistry()](/code/functions/capRegistry.md)

## Called from

- [includes/capabilities.php](/code/files/includes/capabilities.php.md) - in [capGroups()](/code/functions/capGroups.md)

