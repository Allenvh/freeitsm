# ssoClaimValues()

The values of claim `$path` as a list of strings. A dotted path walks nested objects ("realm_access.roles"); a claim whose own name has dots (a URL) is matched whole first. An array gives its scalar members; a string gives itself. Works on the decoded ID token (nested parts are stdClass) and on userinfo.

```php
function ssoClaimValues(array $claims, string $path): array
```

Defined in [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), line 65.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$claims` | `array` |  | the decoded ID token or userinfo claims (nested parts may be stdClass) |
| `$path` | `string` |  | the claim name, or a dotted path into nested objects, e.g. 'groups' or 'realm_access.roles' |

**Returns:** string[]

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)

