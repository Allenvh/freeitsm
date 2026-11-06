# filesNameTaken()

Is a name already used by a live folder or file in this folder? (case-insensitive)

⚠️ Only folders the CALLER can see count. Otherwise "that name is taken" would
confirm a hidden folder exists - at the top level, or a subfolder that stopped
inheriting. Two people may therefore end up with same-named folders they cannot
see each other's of; that is the price of not leaking, and it is harmless.

```php
function filesNameTaken(
    PDO $conn,
    ?int $parentId,
    string $name,
    string $exceptType = '',
    int $exceptId = 0
): bool
```

Defined in [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md), line 147.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$parentId` | `?int` |  | the folder to look in; null is the top level, where only folders are checked |
| `$name` | `string` |  | the name to test, compared case-insensitively |
| `$exceptType` | `string` | `''` | 'folder', 'item' or 'shortcut' - the kind of the thing being renamed; '' for none |
| `$exceptId` | `int` | `0` | the id of that thing, so its own current name does not count as taken |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 150 | `filesNameTaken` | read [files_folders](/code/tables/files_folders.md) |  |
| 158 | `filesNameTaken` | read [files_items](/code/tables/files_items.md) |  |
| 164 | `filesNameTaken` | read [files_shortcuts](/code/tables/files_shortcuts.md) |  |

## Calls

[filesShortcutTarget()](/code/functions/filesShortcutTarget.md)

## Called from

- [api/files/folder.php](/code/files/api/files/folder.php.md)
- [api/files/item.php](/code/files/api/files/item.php.md)
- [api/files/shortcut.php](/code/files/api/files/shortcut.php.md)
- [api/files/upload.php](/code/files/api/files/upload.php.md)
- [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md) - in [filesFreeName()](/code/functions/filesFreeName.md)

