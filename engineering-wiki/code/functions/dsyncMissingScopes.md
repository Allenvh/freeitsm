# dsyncMissingScopes()

Which of the stored branches no longer exist in the directory.

Returns [['dn' => …, 'kind' => 'include'|'exclude'], …].

⚠️ Only checked when the selection came from the OU browser. An install
falling back to sync_base_dn has always behaved this way and an unreadable
base already surfaces as a search failure — warning about it here would
report a "problem" on every run of a perfectly working setup.

A base search returning nothing is treated as gone. That conflates "deleted"
with "the bind account can no longer read it", which is the right call: the
consequence for the import is identical, and so is the thing to go and look at.

```php
function dsyncMissingScopes($ds, array $provider): array
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 229.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ds` |  |  | a bound LDAP connection |
| `$provider` | `array` |  | the auth_providers row whose stored branches are checked |

**Returns:** `array`

## Calls

[dsyncScopes()](/code/functions/dsyncScopes.md)

## Called from

- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [directorySyncRun()](/code/functions/directorySyncRun.md)

