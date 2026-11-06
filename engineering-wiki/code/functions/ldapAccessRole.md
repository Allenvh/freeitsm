# ldapAccessRole()

Decide what an authenticated directory user is allowed to be.

Returns 'analyst', 'user' (self-service requester), or null for no access.

The gate exists because of GitHub #47: with auto-create on, EVERY person the
directory authenticates would otherwise become an analyst. Point that at a
500-person company and you get 500 analysts. Naming an analyst group is what
makes just-in-time creation safe.

Fails CLOSED: once a group is configured, membership must be proven. If the
group read failed for any reason the list is empty, and empty never matches.
When NO groups are configured at all the gate is off and anyone the directory
authenticates is an analyst — that is the single-team default, and the help
page says so plainly.

```php
function ldapAccessRole(array $provider, array $ldapUser): ?string
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 419.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$provider` | `array` |  | the provider row (ldap_analyst_group, ldap_user_group) |
| `$ldapUser` | `array` |  | the 'user' from a successful `ldapAuthenticate()` - its 'groups' are read |

**Returns:** `?string`

## Calls

[ldapInGroup()](/code/functions/ldapInGroup.md)

## Called from

- [api/self-service/login.php](/code/files/api/self-service/login.php.md)
- [api/system/test_ldap_connection.php](/code/files/api/system/test_ldap_connection.php.md)

