# webchatPublicHeaders()

Emit CORS + JSON headers. Pass the request's origin to echo it back (required for the browser to accept the response on a cross-origin call); pass '*' when there is no origin (same-origin / curl). Call once the origin has been authorised.

```php
function webchatPublicHeaders(?string $allowOrigin = null): void
```

Defined in [includes/webchat/public.php](/code/files/includes/webchat/public.php.md), line 64.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$allowOrigin` | `?string` | `null` | the origin to echo in Access-Control-Allow-Origin, or '*'; null or '' sends none |

**Returns:** `void`

## Called from

- [includes/webchat/public.php](/code/files/includes/webchat/public.php.md) - in [webchatHandlePreflight()](/code/functions/webchatHandlePreflight.md), [webchatPublicResolve()](/code/functions/webchatPublicResolve.md)

