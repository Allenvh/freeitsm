# dsyncScopes()

Where this provider imports from: which branches, minus which carve-outs.

A ticked branch means the whole branch, now and in future — so an OU created
under it next year is picked up without anybody remembering to come back
here. That is the behaviour that makes this worth having, and it is also why
carve-outs exist: "everybody in Staff except Contractors" cannot be said with
includes alone once you want new departments to arrive on their own.

⚠️ Falls back to sync_base_dn, then ldap_base_dn. An install upgraded from
before the OU browser has neither column set, and MUST go on importing
exactly who it imported yesterday.

```php
function dsyncScopes(array $provider): array
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 170.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$provider` | `array` |  | the auth_providers row - sync_ou_includes, sync_ou_excludes, sync_base_dn, ldap_base_dn are read |

**Returns:** `array`

## Calls

[dsyncDnIsUnder()](/code/functions/dsyncDnIsUnder.md), [dsyncDnList()](/code/functions/dsyncDnList.md)

## Called from

- [api/system/browse_directory_ous.php](/code/files/api/system/browse_directory_ous.php.md)
- [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [dsyncMissingScopes()](/code/functions/dsyncMissingScopes.md), [dsyncFetchPeople()](/code/functions/dsyncFetchPeople.md)

