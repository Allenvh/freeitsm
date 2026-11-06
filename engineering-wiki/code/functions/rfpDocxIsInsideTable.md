# rfpDocxIsInsideTable()

True if the given element has any <w:tbl> ancestor. Used to skip paragraphs that are inside table cells during the paragraph pass, so they don't get double-counted.

```php
function rfpDocxIsInsideTable(DOMElement $node): bool
```

Defined in [includes/rfp_docx_parser.php](/code/files/includes/rfp_docx_parser.php.md), line 94.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$node` | `DOMElement` |  | a <w:p> paragraph element from document.xml |

**Returns:** `bool`

## Called from

- [includes/rfp_docx_parser.php](/code/files/includes/rfp_docx_parser.php.md) - in [rfpExtractDocxText()](/code/functions/rfpExtractDocxText.md)

