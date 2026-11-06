# webchatParseOrigins()

Parse the stored allowed_origins blob into a clean list of origins. Accepts one per line (or comma-separated), trims trailing slashes, drops blanks/comments.

```php
function webchatParseOrigins(?string $raw): array
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 131.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `?string` |  | the stored allowed_origins text: one origin per line or comma-separated, '#' for comments |

**Returns:** `array`

## Called from

- [api/webchat/save_widget.php](/code/files/api/webchat/save_widget.php.md)
- [includes/webchat/public.php](/code/files/includes/webchat/public.php.md) - in [webchatPublicResolve()](/code/functions/webchatPublicResolve.md)

