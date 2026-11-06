# ctFail()

Sends an HTTP status and {success: false, error}, then stops.

```php
function ctFail(string $e, int $code = 400): void
```

Defined in [api/lms/tests.php](/code/files/api/lms/tests.php.md), line 44.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$e` | `string` |  | the error message, shown to the analyst |
| `$code` | `int` | `400` | the HTTP status to send |

**Returns:** `void`

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md) - in [ctLoadSitting()](/code/functions/ctLoadSitting.md)

