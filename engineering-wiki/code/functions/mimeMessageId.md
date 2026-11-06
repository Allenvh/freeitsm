# mimeMessageId()

A globally unique Message-ID on the sender's own domain, e.g. <3f9c...e1.1790805999@example.com>. Falls back to a fixed domain if the From address has none we can use.

```php
function mimeMessageId(string $from): string
```

Defined in [includes/mime_message.php](/code/files/includes/mime_message.php.md), line 44.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$from` | `string` |  | the From email address whose domain the id uses |

**Returns:** `string`

## Called from

- [includes/mime_message.php](/code/files/includes/mime_message.php.md) - in [mimeBuildMessage()](/code/functions/mimeBuildMessage.md)

