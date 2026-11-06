# oidcRandomToken()

Cryptographically-random URL-safe token.

```php
function oidcRandomToken(int $bytes = 32): string
```

Defined in [includes/oidc.php](/code/files/includes/oidc.php.md), line 59.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$bytes` | `int` | `32` | how many random bytes to draw before encoding |

**Returns:** `string`

## Calls

[oidcBase64Url()](/code/functions/oidcBase64Url.md)

## Called from

- [api/auth/oidc_login.php](/code/files/api/auth/oidc_login.php.md)

