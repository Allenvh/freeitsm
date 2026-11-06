# rfpExtractDocxText()

Extract plain text from a .docx file using ZipArchive + DOMDocument. No external dependencies required.

Output is flat text — paragraphs become lines, table rows become
pipe-separated lines. Heading hierarchy, lists, images, comments,
tracked changes, headers and footers are all dropped. That's enough
for sending to AI for requirement extraction.

```php
function rfpExtractDocxText(string $filePath): string
```

Defined in [includes/rfp_docx_parser.php](/code/files/includes/rfp_docx_parser.php.md), line 21.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$filePath` | `string` |  | the path on disk of the uploaded .docx file |

**Returns:** `string`

## Calls

[rfpDocxIsInsideTable()](/code/functions/rfpDocxIsInsideTable.md), [rfpDocxParagraphText()](/code/functions/rfpDocxParagraphText.md)

## Called from

- [api/rfp-builder/extract_document.php](/code/files/api/rfp-builder/extract_document.php.md)
- [api/rfp-builder/upload_document.php](/code/files/api/rfp-builder/upload_document.php.md)

