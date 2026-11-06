# mimeEncodeHeader()

RFC 2047 encode a header value if it contains non-ASCII.

```php
function mimeEncodeHeader(string $value): string
```

Defined in [includes/mime_message.php](/code/files/includes/mime_message.php.md), line 29.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` | `string` |  | the header text; CR and LF are replaced with spaces |

**Returns:** `string`

## Called from

- [includes/mime_message.php](/code/files/includes/mime_message.php.md) - in [mimeBuildMessage()](/code/functions/mimeBuildMessage.md)

