# webchatPublicResolve()

Load the widget for a public request, enforce active + origin allowlist, and set the CORS headers. Returns the widget row (joined to its channel). Exits with JSON on any failure — so the caller can treat the return as authorised.

```php
function webchatPublicResolve(PDO $conn, string $key): array
```

Defined in [includes/webchat/public.php](/code/files/includes/webchat/public.php.md), line 108.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the public widget key from the request - untrusted; '' fails with 404 |

**Returns:** `array`

## Calls

[webchatFail()](/code/functions/webchatFail.md), [webchatLoadByKey()](/code/functions/webchatLoadByKey.md), [webchatOriginAllowed()](/code/functions/webchatOriginAllowed.md), [webchatParseOrigins()](/code/functions/webchatParseOrigins.md), [webchatPublicHeaders()](/code/functions/webchatPublicHeaders.md), [webchatRequestOrigin()](/code/functions/webchatRequestOrigin.md)

## Called from

- [api/webchat/config.php](/code/files/api/webchat/config.php.md)
- [api/webchat/escalate.php](/code/files/api/webchat/escalate.php.md)
- [api/webchat/poll.php](/code/files/api/webchat/poll.php.md)
- [api/webchat/send.php](/code/files/api/webchat/send.php.md)
- [api/webchat/start.php](/code/files/api/webchat/start.php.md)

