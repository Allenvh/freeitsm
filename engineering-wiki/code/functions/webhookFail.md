# webhookFail()

Sends an HTTP status and {success: false, error} as JSON, then stops.

```php
function webhookFail(int $code, string $msg): void
```

Defined in [api/messaging/webhook.php](/code/files/api/messaging/webhook.php.md), line 71.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$code` | `int` |  | the HTTP status to send |
| `$msg` | `string` |  | the error message |

**Returns:** `void`

## Called from

- [api/messaging/webhook.php](/code/files/api/messaging/webhook.php.md)

