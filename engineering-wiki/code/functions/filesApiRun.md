# filesApiRun()

Run an endpoint body and turn any failure into the UI's {success:false} shape.

```php
function filesApiRun(callable $fn): void
```

Defined in [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md), line 253.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fn` | `callable` |  | the endpoint's work, called with no arguments; it replies itself via `filesApiOk()` |

**Returns:** `void`

## Calls

[filesApiFail()](/code/functions/filesApiFail.md)

## Called from

- [api/files/folder.php](/code/files/api/files/folder.php.md)
- [api/files/item.php](/code/files/api/files/item.php.md)
- [api/files/list.php](/code/files/api/files/list.php.md)
- [api/files/permissions.php](/code/files/api/files/permissions.php.md)
- [api/files/prefs.php](/code/files/api/files/prefs.php.md)
- [api/files/principals.php](/code/files/api/files/principals.php.md)
- [api/files/properties.php](/code/files/api/files/properties.php.md)
- [api/files/recent.php](/code/files/api/files/recent.php.md)
- [api/files/search.php](/code/files/api/files/search.php.md)
- [api/files/settings.php](/code/files/api/files/settings.php.md)
- [api/files/shortcut.php](/code/files/api/files/shortcut.php.md)
- [api/files/transfer.php](/code/files/api/files/transfer.php.md)
- [api/files/upload.php](/code/files/api/files/upload.php.md)
- [api/files/view.php](/code/files/api/files/view.php.md)

