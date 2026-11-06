# webhookDiagnoseError()

Turn a raw cURL transport error into something a human can act on.

A message like "SSL certificate problem: unable to get local issuer
certificate" is perfectly accurate and completely useless to most people —
it says what failed, not what to DO. Worse, its cause usually isn't the
webhook at all: it's that PHP has no CA bundle configured, which on a stock
Windows/WAMP install is the default state. Every outbound HTTPS call in the
product fails the same way, so it's worth naming explicitly.

Returns null when we've nothing useful to add (the raw error stands on its
own), otherwise a diagnosis the UI renders in place of a shrug:
  ['code', 'title', 'summary', 'help' => absolute URL | null]

Called at RENDER time against the stored last_error, so old rows get the
benefit too — no schema column, nothing to backfill.

```php
function webhookDiagnoseError(?string $err): ?array
```

Defined in [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), line 196.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$err` | `?string` |  | the stored last_error (a cURL error or 'HTTP nnn'); null or '' gives null |

**Returns:** `?array`

## Called from

- [api/workflow/deliveries.php](/code/files/api/workflow/deliveries.php.md)
- [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md)

