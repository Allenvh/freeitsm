# ldapGetProvider()

Load an auth_providers row by id with the bind password decrypted. Returns null if not found or not an LDAP provider.

```php
function ldapGetProvider(PDO $conn, int $id): ?array
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 75.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the auth_providers id |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 76 | `ldapGetProvider` | read [auth_providers](/code/tables/auth_providers.md) |  |

## Calls

[decryptValue()](/code/functions/decryptValue.md)

## Called from

- [api/self-service/login.php](/code/files/api/self-service/login.php.md)
- [api/system/test_ldap_connection.php](/code/files/api/system/test_ldap_connection.php.md)
- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapPortalProviders()](/code/functions/ldapPortalProviders.md), [ldapAnalystProviders()](/code/functions/ldapAnalystProviders.md)

