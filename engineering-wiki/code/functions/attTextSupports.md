# attTextSupports()

Could this tier read the file at all? Used to answer before opening anything.

```php
function attTextSupports(string $filename): bool
```

Defined in [includes/search/extract.php](/code/files/includes/search/extract.php.md), line 103.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$filename` | `string` |  | the file name - only its extension is judged |

**Returns:** `bool`

## Calls

[attTextExtension()](/code/functions/attTextExtension.md)

## Called from

- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [documentTextReadable()](/code/functions/documentTextReadable.md), [documentTextDrain()](/code/functions/documentTextDrain.md)
- [includes/search/extract.php](/code/files/includes/search/extract.php.md) - in [attTextExtractFile()](/code/functions/attTextExtractFile.md)
- [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) - in [searchIndexTicketAttachments()](/code/functions/searchIndexTicketAttachments.md)

