# ssoPortalProviderForEmail()

The OIDC provider whose "Email domains" list holds this address's domain, or null. Used only by the portal's email-first router (GH #147).

Enabled OIDC providers only: an LDAP directory has nowhere to redirect to.
Safe before Database Verification (answers null).

```php
function ssoPortalProviderForEmail(PDO $conn, string $email): ?array
```

Defined in [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), line 171.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$email` | `string` |  | the address typed on the portal sign-in page - untrusted; only its domain is used |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 179 | `ssoPortalProviderForEmail` | read [auth_providers](/code/tables/auth_providers.md) |  |

## Calls

[ssoPortalRoutingColumnsReady()](/code/functions/ssoPortalRoutingColumnsReady.md)

## Called from

- [api/auth/resolve_login.php](/code/files/api/auth/resolve_login.php.md)

