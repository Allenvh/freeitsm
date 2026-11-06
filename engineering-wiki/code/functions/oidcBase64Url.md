# oidcBase64Url()

URL-safe base64 (no padding) for PKCE / random tokens.

```php
function oidcBase64Url(string $bin): string
```

Defined in [includes/oidc.php](/code/files/includes/oidc.php.md), line 50.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$bin` | `string` |  | the raw bytes to encode |

**Returns:** `string`

## Called from

- [api/auth/oidc_login.php](/code/files/api/auth/oidc_login.php.md)
- [includes/oidc.php](/code/files/includes/oidc.php.md) - in [oidcRandomToken()](/code/functions/oidcRandomToken.md)

