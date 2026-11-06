# ldapBuildUri()

Build the connection URI, e.g. ldaps://dc1.example.com:636

```php
function ldapBuildUri(array $p): string
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 89.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `array` |  | the provider row from `ldapGetProvider()` (ldap_host, ldap_port, ldap_encryption) |

**Returns:** `string`

## Called from

- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapOpen()](/code/functions/ldapOpen.md)

