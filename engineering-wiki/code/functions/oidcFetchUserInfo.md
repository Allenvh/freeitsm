# oidcFetchUserInfo()

Fetch claims from the provider userinfo_endpoint using the access_token. Returns decoded JSON claims array or empty array on failure.

```php
function oidcFetchUserInfo(string $userinfoEndpoint, string $accessToken): array
```

Defined in [includes/oidc.php](/code/files/includes/oidc.php.md), line 299.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$userinfoEndpoint` | `string` |  | the provider's userinfo_endpoint URL; empty gives [] |
| `$accessToken` | `string` |  | the access token from the token response, sent as a Bearer token; empty gives [] |

**Returns:** `array`

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)

