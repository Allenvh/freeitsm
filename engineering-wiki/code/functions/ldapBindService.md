# ldapBindService()

Bind as the configured read-only service account. Throws with a useful message on failure.

```php
function ldapBindService($ds, array $p): void
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 152.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ds` |  |  | the connection from `ldapOpen()` |
| `$p` | `array` |  | the provider row (ldap_bind_dn, ldap_bind_password decrypted); a blank bind DN binds anonymously |

**Returns:** `void`

## Called from

- [api/system/browse_directory_ous.php](/code/files/api/system/browse_directory_ous.php.md)
- [api/system/test_directory_mapping.php](/code/files/api/system/test_directory_mapping.php.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [directorySyncRun()](/code/functions/directorySyncRun.md)
- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapAuthenticate()](/code/functions/ldapAuthenticate.md), [ldapTestConnection()](/code/functions/ldapTestConnection.md)

