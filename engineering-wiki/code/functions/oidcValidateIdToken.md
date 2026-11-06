# oidcValidateIdToken()

Validate an ID token: signature (JWKS), issuer, audience, nonce, expiry. Returns the validated claims as an associative array. Throws on any failure.

```php
function oidcValidateIdToken(
    string $idToken,
    array $disco,
    array $provider,
    string $expectedNonce
): array
```

Defined in [includes/oidc.php](/code/files/includes/oidc.php.md), line 205.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$idToken` | `string` |  | the raw ID token (JWT) from the token response |
| `$disco` | `array` |  | the provider's discovery document - jwks_uri and issuer are used |
| `$provider` | `array` |  | the auth_providers row - its client_id must be in the audience |
| `$expectedNonce` | `string` |  | the nonce stored in the session when the sign-in started |

**Returns:** `array`

## Calls

[oidcHttpGet()](/code/functions/oidcHttpGet.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)

