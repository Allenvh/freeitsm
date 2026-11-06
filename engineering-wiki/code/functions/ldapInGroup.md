# ldapInGroup()

Is the user in `$wanted`? `$wanted` may be a plain group name ("NW-IT-Support") or a full DN — admins reach for either, so accept both. Matching is case-insensitive because directories treat these names that way.

```php
function ldapInGroup(array $groups, string $wanted): bool
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 280.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$groups` | `array` |  | the user's groups from `ldapUserGroups()`, each ['dn' => ..., 'cn' => ...] |
| `$wanted` | `string` |  | the group to look for, as a plain name or a full DN; '' gives false |

**Returns:** `bool`

## Called from

- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapAccessRole()](/code/functions/ldapAccessRole.md)

