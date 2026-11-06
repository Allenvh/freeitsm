# dsyncFetchPeople()

Reads every person under the provider's ticked directory branches with a paged LDAP search, skipping carved-out branches and counting each DN once.

Throws when nothing is ticked, a branch cannot be searched, or more than DSYNC_MAX_PEOPLE match.

```php
function dsyncFetchPeople($ds, array $provider): array
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 278.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ds` |  |  | a bound LDAP connection |
| `$provider` | `array` |  | the auth_providers row being synced (its ldap_attr_* and sync_* columns) |

**Returns:** array  raw LDAP entries

## Calls

[dsyncAttr()](/code/functions/dsyncAttr.md), [dsyncDnIsExcluded()](/code/functions/dsyncDnIsExcluded.md), [dsyncScopes()](/code/functions/dsyncScopes.md)

## Called from

- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [directorySyncRun()](/code/functions/directorySyncRun.md)

