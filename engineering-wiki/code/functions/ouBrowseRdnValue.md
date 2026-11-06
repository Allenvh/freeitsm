# ouBrowseRdnValue()

The readable half of a DN's first component: "OU=Staff,…" → "Staff".

```php
function ouBrowseRdnValue(string $dn): string
```

Defined in [api/system/browse_directory_ous.php](/code/files/api/system/browse_directory_ous.php.md), line 212.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dn` | `string` |  | an LDAP distinguished name, e.g. OU=Staff,DC=example,DC=com |

**Returns:** `string`

## Called from

- [api/system/browse_directory_ous.php](/code/files/api/system/browse_directory_ous.php.md)

