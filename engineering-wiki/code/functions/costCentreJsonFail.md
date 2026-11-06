# costCentreJsonFail()

Sends an HTTP status (200 by default) and {success: false, error} as JSON, then stops.

```php
function costCentreJsonFail(string $message, int $status = 200): void
```

Defined in [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md), line 45.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$message` | `string` |  | the error shown to the user |
| `$status` | `int` | `200` | the HTTP status to send |

**Returns:** `void`

## Called from

- [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md)

