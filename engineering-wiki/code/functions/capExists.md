# capExists()

Is this a capability the code defines? Enum twin: Capability::tryFrom() !== null.

```php
function capExists(string $key): bool
```

Defined in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 427.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | a capability key, e.g. 'assets.manage' |

**Returns:** `bool`

## Calls

[capRegistry()](/code/functions/capRegistry.md)

## Called from

- [includes/capabilities.php](/code/files/includes/capabilities.php.md) - in [capFromKey()](/code/functions/capFromKey.md), [capSelfCheck()](/code/functions/capSelfCheck.md)

