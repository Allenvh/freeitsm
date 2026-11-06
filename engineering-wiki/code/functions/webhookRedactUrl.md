# webhookRedactUrl()

A URL safe to show in the delivery log: keeps the bit that identifies WHERE it went, hides the bit that lets you POST there. A Discord webhook URL ends in a token; printing it in a log everyone can read defeats encrypting it.

https://discord.com/api/webhooks/142.../aBcD-secret  →  https://discord.com/api/webhooks/…/••••

```php
function webhookRedactUrl(?string $url): string
```

Defined in [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), line 82.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `?string` |  | the decrypted webhook URL |

**Returns:** `string`

## Called from

- [api/workflow/deliveries.php](/code/files/api/workflow/deliveries.php.md)

