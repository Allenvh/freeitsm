# filesCleanName()

A file or folder name as a person typed it, made safe to store and display.

```php
function filesCleanName(string $name): string
```

Defined in [includes/files/storage.php](/code/files/includes/files/storage.php.md), line 120.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | the name as typed or uploaded (untrusted) |

**Returns:** `string`

## Calls

[uploadCleanName()](/code/functions/uploadCleanName.md)

## Called from

- [api/files/folder.php](/code/files/api/files/folder.php.md)
- [api/files/item.php](/code/files/api/files/item.php.md)
- [api/files/shortcut.php](/code/files/api/files/shortcut.php.md)
- [api/files/upload.php](/code/files/api/files/upload.php.md)

