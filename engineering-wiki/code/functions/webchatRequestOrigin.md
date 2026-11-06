# webchatRequestOrigin()

The origin of the requesting page. Cross-origin requests carry an Origin header (the authoritative case in production, where the customer's site and the FreeITSM host are different origins). Same-origin requests — e.g. a demo site on the same host — omit Origin entirely, so we fall back to the scheme+host of the Referer. Returns '' if neither is present. Like the whole allowlist, this is a deterrent, not a crypto control.

```php
function webchatRequestOrigin(): string
```

Defined in [includes/webchat/public.php](/code/files/includes/webchat/public.php.md), line 37.

**Returns:** `string`

## Called from

- [includes/webchat/public.php](/code/files/includes/webchat/public.php.md) - in [webchatPublicResolve()](/code/functions/webchatPublicResolve.md)

