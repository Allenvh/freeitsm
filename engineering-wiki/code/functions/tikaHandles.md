# tikaHandles()

Would Tika be asked about this file, if configured?

```php
function tikaHandles(string $filename): bool
```

Defined in [includes/search/tika.php](/code/files/includes/search/tika.php.md), line 110.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$filename` | `string` |  | the attachment's file name; only its extension is checked against TIKA_EXTENSIONS |

**Returns:** `bool`

## Calls

[attTextExtension()](/code/functions/attTextExtension.md)

## Called from

- [api/system/tika_settings.php](/code/files/api/system/tika_settings.php.md)
- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [documentTextReadable()](/code/functions/documentTextReadable.md)
- [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md) - in [extractQueueDrain()](/code/functions/extractQueueDrain.md)
- [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) - in [searchIndexTicketAttachments()](/code/functions/searchIndexTicketAttachments.md)

