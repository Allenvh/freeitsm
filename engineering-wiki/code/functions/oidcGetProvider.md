# oidcGetProvider()

Load a provider row by id, with the client secret decrypted. Returns null if not found.

```php
function oidcGetProvider(PDO $conn, int $id): ?array
```

Defined in [includes/oidc.php](/code/files/includes/oidc.php.md), line 69.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the auth_providers id |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 70 | `oidcGetProvider` | read [auth_providers](/code/tables/auth_providers.md) |  |

## Calls

[decryptValue()](/code/functions/decryptValue.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)
- [api/auth/oidc_login.php](/code/files/api/auth/oidc_login.php.md)
- [self-service/logout.php](/code/files/self-service/logout.php.md)

