# ldapUserGroups()

Every group the user belongs to, as ['dn' => ..., 'cn' => ...].

Asks the GROUPS who their members are, rather than reading memberOf off the
user. That is deliberate:
  - OpenLDAP has no memberOf at all without the memberof overlay loaded;
  - on AD, memberOf lists DIRECT groups only, so a user in "IT-Support",
    which is itself inside "All-Staff", does not show "All-Staff" at all.
The AD default filter uses LDAP_MATCHING_RULE_IN_CHAIN, which walks the
nesting properly. Returns [] when no group filter is configured.

Must be called on a connection bound as the SERVICE account: the user's own
bind may not be permitted to read group objects.

```php
function ldapUserGroups($ds, array $p, string $userDn): array
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 246.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ds` |  |  | a connection from `ldapOpen()`, bound as the service account |
| `$p` | `array` |  | the provider row (ldap_group_filter, ldap_group_base_dn, ldap_base_dn) |
| `$userDn` | `string` |  | the user's full DN, substituted (escaped) for %s in the group filter |

**Returns:** `array`

## Called from

- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapAuthenticate()](/code/functions/ldapAuthenticate.md)

