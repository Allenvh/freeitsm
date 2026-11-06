# ouBrowseParent()

The DN one level up. '' once there is nothing left.

```php
function ouBrowseParent(string $dn): string
```

Defined in [api/system/browse_directory_ous.php](/code/files/api/system/browse_directory_ous.php.md), line 198.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dn` | `string` |  | an LDAP distinguished name, e.g. OU=Staff,DC=example,DC=com |

**Returns:** `string`

## Called from

- [api/system/browse_directory_ous.php](/code/files/api/system/browse_directory_ous.php.md) - in [ouBrowseIsPlumbing()](/code/functions/ouBrowseIsPlumbing.md)

