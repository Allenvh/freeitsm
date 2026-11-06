# webchatOriginAllowed()

Is the request origin permitted for this widget? An empty allowlist means "any" (testing only). Matching is exact on scheme+host (+port), trailing slash ignored.

```php
function webchatOriginAllowed(array $allowed, ?string $origin): bool
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 155.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$allowed` | `array` |  | the allowlist from `webchatParseOrigins()`; empty allows any origin |
| `$origin` | `?string` |  | the request's Origin, e.g. 'https://example.com'; null or '' is refused when a list exists |

**Returns:** `bool`

## Called from

- [includes/webchat/public.php](/code/files/includes/webchat/public.php.md) - in [webchatPublicResolve()](/code/functions/webchatPublicResolve.md)

