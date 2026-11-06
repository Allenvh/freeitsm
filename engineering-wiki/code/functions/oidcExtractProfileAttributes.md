# oidcExtractProfileAttributes()

Pull the five profile fields out of OIDC claims. Each IdP names them its own way (Entra `jobTitle`/`businessPhones`, Okta `title`, Keycloak `job_title`, the standard `phone_number`), so each field tries the known spellings in turn. Values are trimmed and cut to the column length; missing = null.

```php
function oidcExtractProfileAttributes(array $claims): array
```

Defined in [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md), line 411.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$claims` | `array` |  | the OIDC claims (ID token merged with userinfo), keyed by claim name |

**Returns:** `array`

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md) - in [oidcSyncProfile()](/code/functions/oidcSyncProfile.md), [completeSelfServiceSso()](/code/functions/completeSelfServiceSso.md), [oidcCreateAnalyst()](/code/functions/oidcCreateAnalyst.md)

