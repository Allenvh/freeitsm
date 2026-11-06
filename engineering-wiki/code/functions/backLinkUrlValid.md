# backLinkUrlValid()

True when `$url` is a plain http(s) address - the only kind ever rendered.

```php
function backLinkUrlValid(string $url): bool
```

Defined in [includes/back_link.php](/code/files/includes/back_link.php.md), line 22.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the stored or posted address to check |

**Returns:** `bool`

## Called from

- [api/system/save_branding.php](/code/files/api/system/save_branding.php.md)
- [includes/back_link.php](/code/files/includes/back_link.php.md) - in [backLinkHtml()](/code/functions/backLinkHtml.md)

