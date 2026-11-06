# ssoGroupDisplay()

A group's name if it has one, else the code - for messages and the last-sign-in line.

```php
function ssoGroupDisplay(array $seen, string $group): string
```

Defined in [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), line 200.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$seen` | `array` |  | the provider's groups, from `ssoSeenGroups()` |
| `$group` | `string` |  | the group code to name |

**Returns:** `string`

## Calls

[ssoSameGroup()](/code/functions/ssoSameGroup.md)

*Nothing calls this function by name.*

