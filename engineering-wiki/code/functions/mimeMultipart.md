# mimeMultipart()

Wrap [headers, content] entities in a multipart/<subtype> entity.

```php
function mimeMultipart(string $subtype, array $entities, string $extraParams = ''): array
```

Defined in [includes/mime_message.php](/code/files/includes/mime_message.php.md), line 167.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$subtype` | `string` |  | the multipart subtype, e.g. 'mixed' or 'related' |
| `$entities` | `array` |  | the parts to wrap, each a [headers list, content string] pair |
| `$extraParams` | `string` | `''` | extra Content-Type parameters, e.g. 'type="text/html"'; '' for none |

**Returns:** `array`

## Called from

- [includes/mime_message.php](/code/files/includes/mime_message.php.md) - in [mimeBuildMessage()](/code/functions/mimeBuildMessage.md)

