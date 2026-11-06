# ssoSameGroup()

The same group, ignoring case in any alphabet and stray spaces.

```php
function ssoSameGroup(string $a, string $b): bool
```

Defined in [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), line 99.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$a` | `string` |  | one group name or id |
| `$b` | `string` |  | the other; a blank `$a` never matches |

**Returns:** `bool`

## Called from

- [includes/sso_claims.php](/code/files/includes/sso_claims.php.md) - in [ssoInGroup()](/code/functions/ssoInGroup.md), [ssoGroupDisplay()](/code/functions/ssoGroupDisplay.md)

