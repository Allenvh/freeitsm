# ldapOpen()

Open a connection and apply the options that matter. Throws on failure. Does NOT bind.

```php
function ldapOpen(array $p)
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 120.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `array` |  | the provider row from `ldapGetProvider()` (host, port, encryption) |

## Calls

[ldapBuildUri()](/code/functions/ldapBuildUri.md), [ldapExtensionAvailable()](/code/functions/ldapExtensionAvailable.md)

## Called from

- [api/system/browse_directory_ous.php](/code/files/api/system/browse_directory_ous.php.md)
- [api/system/test_directory_mapping.php](/code/files/api/system/test_directory_mapping.php.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [directorySyncRun()](/code/functions/directorySyncRun.md)
- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapAuthenticate()](/code/functions/ldapAuthenticate.md), [ldapTestConnection()](/code/functions/ldapTestConnection.md)

