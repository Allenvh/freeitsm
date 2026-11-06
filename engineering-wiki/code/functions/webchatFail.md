# webchatFail()

Emit a JSON error and stop.

```php
function webchatFail(string $msg, int $code = 400): void
```

Defined in [includes/webchat/public.php](/code/files/includes/webchat/public.php.md), line 94.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$msg` | `string` |  | the error text for the widget, safe to show a visitor |
| `$code` | `int` | `400` | the HTTP status code |

**Returns:** `void`

## Called from

- [api/webchat/escalate.php](/code/files/api/webchat/escalate.php.md)
- [api/webchat/poll.php](/code/files/api/webchat/poll.php.md)
- [api/webchat/send.php](/code/files/api/webchat/send.php.md)
- [api/webchat/start.php](/code/files/api/webchat/start.php.md)
- [includes/webchat/public.php](/code/files/includes/webchat/public.php.md) - in [webchatPublicResolve()](/code/functions/webchatPublicResolve.md), [webchatRateLimitStart()](/code/functions/webchatRateLimitStart.md), [webchatRateLimitSend()](/code/functions/webchatRateLimitSend.md)

