# ldapResolveAnalyst()

Map an authenticated directory user onto an analyst id, creating one if the provider allows it. Mirrors the OIDC resolution order: 1) existing link by (provider, subject), 2) match an existing analyst by email — STRICTLY isolated: the analyst must already be assigned to this provider, 3) just-in-time create, if auto_create_analysts is on (auto_create_users is the PORTAL switch - see `ldapResolveUser()`).

Returns ['ok' => true, 'analyst_id' => int] or ['ok' => false, 'error' => string].

```php
function ldapResolveAnalyst(PDO $conn, array $provider, array $ldapUser): array
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 615.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$provider` | `array` |  | the provider row from `ldapGetProvider()` |
| `$ldapUser` | `array` |  | the 'user' from a successful `ldapAuthenticate()` (guid, username, email, name) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 621 | `ldapResolveAnalyst` | read [analyst_sso_identities](/code/tables/analyst_sso_identities.md) |  |
| 630 | `ldapResolveAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 648 | `ldapResolveAnalyst` | **update** [analyst_sso_identities](/code/tables/analyst_sso_identities.md) (`last_login_datetime`, `email`) |  |
| 651 | `ldapResolveAnalyst` | **update** [analysts](/code/tables/analysts.md) (`email`) |  |
| 659 | `ldapResolveAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 700 | `ldapResolveAnalyst` | **insert** [analyst_sso_identities](/code/tables/analyst_sso_identities.md) (`analyst_id`, `provider_id`, `subject`, `email`, `linked_datetime`, `last_login_datetime`) |  |

## Calls

[ldapCreateAnalyst()](/code/functions/ldapCreateAnalyst.md), [ssoClearDanglingLink()](/code/functions/ssoClearDanglingLink.md)

*Nothing calls this function by name.*

