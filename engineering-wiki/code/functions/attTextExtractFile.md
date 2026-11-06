# attTextExtractFile()

Read the text of one file.

Never throws: an attachment that cannot be read is a status, not an error, and
must not take down the indexing of the ticket it belongs to.

```php
function attTextExtractFile(
    string $path,
    string $filename,
    int $maxChars = ATT_TEXT_MAX_CHARS
): array
```

Defined in [includes/search/extract.php](/code/files/includes/search/extract.php.md), line 137.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | the file's path on disk, already checked to be inside the uploads directory |
| `$filename` | `string` |  | the file's original name - its extension picks the reader |
| `$maxChars` | `int` | `ATT_TEXT_MAX_CHARS` | the most characters to keep; longer text is cut and marked truncated |

**Returns:** array{status:string,text:string}

## Calls

[attTextExtension()](/code/functions/attTextExtension.md), [attTextExtractOoxml()](/code/functions/attTextExtractOoxml.md), [attTextNormalise()](/code/functions/attTextNormalise.md), [attTextSupports()](/code/functions/attTextSupports.md)

## Called from

- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [documentTextDrain()](/code/functions/documentTextDrain.md)
- [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) - in [searchIndexTicketAttachments()](/code/functions/searchIndexTicketAttachments.md)

