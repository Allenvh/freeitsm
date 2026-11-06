# ouBrowseIsPlumbing()

Active Directory's own furniture, which nobody is importing people from.

Only containers directly under the root are judged: an OU somebody has
genuinely called "System" deeper in their own tree is theirs, not ours to
hide. Being wrong here hides part of somebody's directory, so the list is
deliberately short and only covers what AD creates itself.

```php
function ouBrowseIsPlumbing(string $dn, string $root): bool
```

Defined in [api/system/browse_directory_ous.php](/code/files/api/system/browse_directory_ous.php.md), line 230.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dn` | `string` |  | the distinguished name of a container found by the search |
| `$root` | `string` |  | the base DN being browsed; only containers directly under it are judged |

**Returns:** `bool`

## Calls

[ouBrowseParent()](/code/functions/ouBrowseParent.md)

## Called from

- [api/system/browse_directory_ous.php](/code/files/api/system/browse_directory_ous.php.md)

