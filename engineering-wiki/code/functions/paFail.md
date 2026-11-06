# paFail()

Sends an HTTP status (200 by default) and {success: false, error} as JSON, then stops.

```php
function paFail(string $message, int $status = 200): void
```

Defined in [api/system/photo_album.php](/code/files/api/system/photo_album.php.md), line 41.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$message` | `string` |  | the error shown to the user |
| `$status` | `int` | `200` | the HTTP status to send |

**Returns:** `void`

## Called from

- [api/system/photo_album.php](/code/files/api/system/photo_album.php.md)

