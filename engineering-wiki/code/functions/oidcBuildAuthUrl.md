# oidcBuildAuthUrl()

Build the authorization URL to redirect the user's browser to.

```php
function oidcBuildAuthUrl(
    array $provider,
    array $disco,
    string $state,
    string $nonce,
    string $codeChallenge
): string
```

Defined in [includes/oidc.php](/code/files/includes/oidc.php.md), line 146.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$provider` | `array` |  | the auth_providers row - client_id and scopes are used |
| `$disco` | `array` |  | the provider's discovery document from `oidcDiscover()` |
| `$state` | `string` |  | the random value the callback checks against the session (CSRF protection) |
| `$nonce` | `string` |  | the random value the ID token must echo back |
| `$codeChallenge` | `string` |  | the PKCE S256 challenge derived from the code verifier |

**Returns:** `string`

## Calls

[oidcRedirectUri()](/code/functions/oidcRedirectUri.md)

## Called from

- [api/auth/oidc_login.php](/code/files/api/auth/oidc_login.php.md)

