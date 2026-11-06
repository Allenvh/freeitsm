# mediaFail()

Sends the given HTTP status with a plain "Not found", then stops - every refusal looks the same, so nothing is revealed about why.

```php
function mediaFail(int $code): void
```

Defined in [api/messaging/media.php](/code/files/api/messaging/media.php.md), line 33.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$code` | `int` |  | the HTTP status to send, e.g. 400, 404 or 410 for an expired link |

**Returns:** `void`

## Called from

- [api/messaging/media.php](/code/files/api/messaging/media.php.md)

