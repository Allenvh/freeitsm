# ldapAnalystProviders()

Every enabled, analyst-facing LDAP provider, in display order. tenant_id IS NULL = global/analyst-facing, matching how the OIDC analyst login lists providers.

```php
function ldapAnalystProviders(PDO $conn): array
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 520.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 523 | `ldapAnalystProviders` | read [auth_providers](/code/tables/auth_providers.md) |  |

## Calls

[ldapGetProvider()](/code/functions/ldapGetProvider.md)

*Nothing calls this function by name.*

