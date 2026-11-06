# filesApiFail()

Sends a failure JSON reply ({success: false, error}) with an optional HTTP status and extra keys, and ends the request.

```php
function filesApiFail(string $message, int $status = 200, array $extra = []): void
```

Defined in [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md), line 63.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$message` | `string` |  | the error text shown to the user |
| `$status` | `int` | `200` | the HTTP status; 200 (the default) leaves it unchanged |
| `$extra` | `array` | `[]` | extra keys merged into the reply beside success and error |

**Returns:** `void`

## Called from

- [api/files/folder.php](/code/files/api/files/folder.php.md)
- [api/files/item.php](/code/files/api/files/item.php.md)
- [api/files/list.php](/code/files/api/files/list.php.md)
- [api/files/permissions.php](/code/files/api/files/permissions.php.md)
- [api/files/prefs.php](/code/files/api/files/prefs.php.md)
- [api/files/settings.php](/code/files/api/files/settings.php.md)
- [api/files/shortcut.php](/code/files/api/files/shortcut.php.md)
- [api/files/transfer.php](/code/files/api/files/transfer.php.md)
- [api/files/upload.php](/code/files/api/files/upload.php.md)
- [api/files/view.php](/code/files/api/files/view.php.md)
- [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md) - in [filesNeed()](/code/functions/filesNeed.md), [filesNeedItem()](/code/functions/filesNeedItem.md), [filesApiRun()](/code/functions/filesApiRun.md)

