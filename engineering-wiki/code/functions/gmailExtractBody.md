# gmailExtractBody()

Extract body (prefer HTML, fall back to plain text).

```php
function gmailExtractBody(array $payload): string
```

Defined in [includes/gmail.php](/code/files/includes/gmail.php.md), line 470.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$payload` | `array` |  | a Gmail message payload or one of its multipart parts (mimeType, body.data, parts) |

**Returns:** `string`

## Called from

- [includes/gmail.php](/code/files/includes/gmail.php.md) - in [gmailGetMessage()](/code/functions/gmailGetMessage.md), [gmailExtractBody()](/code/functions/gmailExtractBody.md)

