# dsyncMapPerson()

Turn a raw directory entry into the fields FreeITSM stores. Returns null when the entry has no usable identity at all.

```php
function dsyncMapPerson(array $entry, array $provider): ?array
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 380.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$entry` | `array` |  | one raw entry from `ldap_get_entries()` |
| `$provider` | `array` |  | the auth_providers row being synced (its ldap_attr_* and sync_* columns) |

**Returns:** `?array`

## Calls

[dsyncAttr()](/code/functions/dsyncAttr.md), [dsyncValue()](/code/functions/dsyncValue.md), [ldapStringifyId()](/code/functions/ldapStringifyId.md)

## Called from

- [api/system/test_directory_mapping.php](/code/files/api/system/test_directory_mapping.php.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [directorySyncRun()](/code/functions/directorySyncRun.md)

