# oidcExchangeCode()

Exchange the authorization code for tokens at the token endpoint (server-to-server back-channel). Returns the decoded token response. Throws on HTTP / provider error.

```php
function oidcExchangeCode(array $provider, array $disco, string $code, string $codeVerifier): array
```

Defined in [includes/oidc.php](/code/files/includes/oidc.php.md), line 170.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$provider` | `array` |  | the auth_providers row with client_secret decrypted (oidcGetProvider()) |
| `$disco` | `array` |  | the provider's discovery document from `oidcDiscover()` |
| `$code` | `string` |  | the authorization code the provider sent to the callback |
| `$codeVerifier` | `string` |  | the PKCE code verifier kept in the session at sign-in start |

**Returns:** `array`

## Calls

[oidcHttpPost()](/code/functions/oidcHttpPost.md), [oidcRedirectUri()](/code/functions/oidcRedirectUri.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)

