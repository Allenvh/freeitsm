# webhookDecrypt()

Decrypt; plaintext passes straight through (pre-encryption rows still work).

```php
function webhookDecrypt(?string $v): ?string
```

Defined in [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), line 64.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` | `?string` |  | a stored value, encrypted or (on older rows) plain; null and '' come back unchanged |

**Returns:** `?string`

## Calls

[decryptValue()](/code/functions/decryptValue.md)

## Called from

- [api/workflow/deliveries.php](/code/files/api/workflow/deliveries.php.md)
- [api/workflow/get.php](/code/files/api/workflow/get.php.md)
- [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md)
- [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md) - in [webhookAttemptDelivery()](/code/functions/webhookAttemptDelivery.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_send_webhook`

