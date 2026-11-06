# capIsSensitive()

Does granting this need a second thought? Enum twin: `$cap`->isSensitive().

```php
function capIsSensitive(string $key): bool
```

Defined in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 484.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | a capability key; an unknown one is not sensitive |

**Returns:** `bool`

## Calls

[capRegistry()](/code/functions/capRegistry.md)

## Called from

- [includes/capabilities.php](/code/files/includes/capabilities.php.md) - in [capGroups()](/code/functions/capGroups.md)

