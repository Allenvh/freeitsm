# oidcDiscover()

Fetch + cache (per request) a provider's discovery document. Throws on failure.

```php
function oidcDiscover(string $issuer): array
```

Defined in [includes/oidc.php](/code/files/includes/oidc.php.md), line 84.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$issuer` | `string` |  | the provider's issuer URL; a trailing slash is ignored |

**Returns:** `array`

## Calls

[oidcHttpGet()](/code/functions/oidcHttpGet.md), [oidcIsAbsoluteHttpUrl()](/code/functions/oidcIsAbsoluteHttpUrl.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)
- [api/auth/oidc_login.php](/code/files/api/auth/oidc_login.php.md)
- [self-service/logout.php](/code/files/self-service/logout.php.md)

