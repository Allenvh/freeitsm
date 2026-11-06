# filesNeedItem()

A file the caller holds `$need` on, or "not found".

```php
function filesNeedItem(PDO $conn, int $analystId, int $itemId, int $need, string $what = ''): array
```

Defined in [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md), line 124.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$itemId` | `int` |  | the files_items id being acted on |
| `$need` | `int` |  | the lowest level the action needs on the file's folder, FilesAcl::VIEW to FilesAcl::FULL |
| `$what` | `string` | `''` | the action being attempted, recorded as 'doing' in any 'denied' audit row |

**Returns:** `array`

## Calls

[filesApiFail()](/code/functions/filesApiFail.md), [filesItem()](/code/functions/filesItem.md), [filesNeed()](/code/functions/filesNeed.md)

## Called from

- [api/files/item.php](/code/files/api/files/item.php.md)
- [api/files/properties.php](/code/files/api/files/properties.php.md)
- [api/files/view.php](/code/files/api/files/view.php.md)

