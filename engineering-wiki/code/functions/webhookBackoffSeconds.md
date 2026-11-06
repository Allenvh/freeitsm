# webhookBackoffSeconds()

Seconds to wait before the next retry, keyed by the attempt that just failed.

```php
function webhookBackoffSeconds(int $failedAttempt): int
```

Defined in [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), line 172.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$failedAttempt` | `int` |  | the attempt number that just failed, from 1; beyond 5 waits 6 hours |

**Returns:** `int`

## Called from

- [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md) - in [webhookAttemptDelivery()](/code/functions/webhookAttemptDelivery.md)

