# ssoInGroup()

Is `$wanted` among `$groups`?

```php
function ssoInGroup(array $groups, string $wanted): bool
```

Defined in [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), line 111.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$groups` | `array` |  | the group values the sign-in sent, from `ssoClaimValues()` |
| `$wanted` | `string` |  | the group to look for, matched with `ssoSameGroup()` |

**Returns:** `bool`

## Calls

[ssoSameGroup()](/code/functions/ssoSameGroup.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)
- [includes/sso_claims.php](/code/files/includes/sso_claims.php.md) - in [ssoApplyGroupAccess()](/code/functions/ssoApplyGroupAccess.md)

