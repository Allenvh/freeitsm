# webchatHandlePreflight()

Answer a CORS preflight and exit. Call at the very top of each public endpoint.

```php
function webchatHandlePreflight(): void
```

Defined in [includes/webchat/public.php](/code/files/includes/webchat/public.php.md), line 77.

**Returns:** `void`

## Calls

[webchatPublicHeaders()](/code/functions/webchatPublicHeaders.md)

## Called from

- [api/webchat/config.php](/code/files/api/webchat/config.php.md)
- [api/webchat/escalate.php](/code/files/api/webchat/escalate.php.md)
- [api/webchat/poll.php](/code/files/api/webchat/poll.php.md)
- [api/webchat/send.php](/code/files/api/webchat/send.php.md)
- [api/webchat/start.php](/code/files/api/webchat/start.php.md)

