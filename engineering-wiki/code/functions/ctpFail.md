# ctpFail()

Sends an HTTP status and {success: false, error: `$code`} - a short code such as 'finished' that the candidate page acts on - then stops.

```php
function ctpFail(string $code, int $http = 400): void
```

Defined in [api/lms/test_public.php](/code/files/api/lms/test_public.php.md), line 35.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$code` | `string` |  | the short error code the candidate page acts on, e.g. 'finished' |
| `$http` | `int` | `400` | the HTTP status to send |

**Returns:** `void`

## Called from

- [api/lms/test_public.php](/code/files/api/lms/test_public.php.md)

