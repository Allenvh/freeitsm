# ldapFindUser()

Find exactly one directory entry for `$login`. Returns the raw entry array (from ldap_get_entries) or null when not found. Throws when the search itself fails or matches more than one user.

```php
function ldapFindUser($ds, array $p, string $login): ?array
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 179.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ds` |  |  | a connection from `ldapOpen()`, already bound as the service account |
| `$p` | `array` |  | the provider row (ldap_base_dn, ldap_user_filter, ldap_attr_*) |
| `$login` | `string` |  | what the person typed as their username; escaped before it goes into the filter |

**Returns:** `?array`

## Called from

- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapAuthenticate()](/code/functions/ldapAuthenticate.md)

