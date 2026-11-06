# ldapAuthenticate()

Authenticate `$login`/`$password` against the directory.

Returns:
  ['ok' => true,  'user' => ['dn','guid','username','email','name','groups']]
  ['ok' => false, 'error' => string, 'reason' => 'credentials'|'config'|'notfound']

'reason' lets the caller decide what to reveal: a login form should say
"wrong username or password" for everything, while the admin Test button
wants the detail.

```php
function ldapAuthenticate(array $provider, string $login, string $password): array
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 307.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$provider` | `array` |  | the provider row from `ldapGetProvider()` |
| `$login` | `string` |  | what the person typed as their username (trimmed here) |
| `$password` | `string` |  | what the person typed as their password; empty or containing a NUL byte is refused |

**Returns:** `array`

## Calls

[ldapAttr()](/code/functions/ldapAttr.md), [ldapBindService()](/code/functions/ldapBindService.md), [ldapFindUser()](/code/functions/ldapFindUser.md), [ldapOpen()](/code/functions/ldapOpen.md), [ldapStringifyId()](/code/functions/ldapStringifyId.md), [ldapUserGroups()](/code/functions/ldapUserGroups.md)

## Called from

- [api/self-service/login.php](/code/files/api/self-service/login.php.md)
- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapTestConnection()](/code/functions/ldapTestConnection.md)

