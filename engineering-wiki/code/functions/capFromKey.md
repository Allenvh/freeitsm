# capFromKey()

Resolve a key that came from OUTSIDE the code (a DB row, a form post) to a known capability, or null if the code no longer defines it. Enum twin: Capability::tryFrom().

This is the trust boundary: everything read from rbac_role_capabilities goes through
here, so a stale or hand-inserted key can never grant anything.

```php
function capFromKey(string $key): ?string
```

Defined in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 441.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | a capability key read from outside the code (rbac_role_capabilities or a form post) - untrusted |

**Returns:** `?string`

## Calls

[capAliases()](/code/functions/capAliases.md), [capExists()](/code/functions/capExists.md)

## Called from

- [api/system/role.php](/code/files/api/system/role.php.md)
- [includes/rbac.php](/code/files/includes/rbac.php.md) - in [getAnalystCapabilities()](/code/functions/getAnalystCapabilities.md)
- [includes/services/analysts.php](/code/files/includes/services/analysts.php.md) - in `AnalystsService::capabilityKeys`

