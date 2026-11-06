# filesNewStoragePath()

A fresh relative path for today: 2026/10/10/<hex>.bin (directories created).

```php
function filesNewStoragePath(PDO $conn): string
```

Defined in [includes/files/storage.php](/code/files/includes/files/storage.php.md), line 77.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[filesStorageRoot()](/code/functions/filesStorageRoot.md)

## Called from

- [api/files/upload.php](/code/files/api/files/upload.php.md)

