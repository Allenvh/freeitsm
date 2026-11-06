# webhookHttpSend()

Perform the outbound HTTP request for a webhook and return the raw outcome. Shared by the delivery worker and the editor's "Send test" preview so both use identical transport (method, timeouts, TLS verification) — the test can never behave differently from a real delivery.

```php
function webhookHttpSend(string $url, array $headers, string $body, string $method = 'POST'): array
```

Defined in [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), line 299.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the full endpoint URL, decrypted |
| `$headers` | `array` |  | header lines, e.g. 'Content-Type: application/json' |
| `$body` | `string` |  | the request body as sent |
| `$method` | `string` | `'POST'` | the HTTP method; '' means POST |

**Returns:** array{body:string|false,status:int,error:string,ms:int}

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md)
- [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md) - in [webhookAttemptDelivery()](/code/functions/webhookAttemptDelivery.md)

