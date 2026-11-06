# dsyncAttr()

The configured attribute name for one field, falling back to the flavour default.

```php
function dsyncAttr(array $provider, string $field): string
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 117.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$provider` | `array` |  | the auth_providers row |
| `$field` | `string` |  | a `dsyncAttrDefaults()` key, e.g. 'email' - read from the ldap_attr_<field> column |

**Returns:** `string`

## Calls

[dsyncAttrDefaults()](/code/functions/dsyncAttrDefaults.md), [dsyncFlavour()](/code/functions/dsyncFlavour.md)

## Called from

- [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md)
- [api/system/test_directory_mapping.php](/code/files/api/system/test_directory_mapping.php.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [dsyncFetchPeople()](/code/functions/dsyncFetchPeople.md), [dsyncMapPerson()](/code/functions/dsyncMapPerson.md)

