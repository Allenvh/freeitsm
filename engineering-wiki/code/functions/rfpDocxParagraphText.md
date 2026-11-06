# rfpDocxParagraphText()

Concatenate all text runs in a single <w:p>, preserving tab characters where <w:tab/> elements appear.

```php
function rfpDocxParagraphText(DOMElement $p, string $ns): string
```

Defined in [includes/rfp_docx_parser.php](/code/files/includes/rfp_docx_parser.php.md), line 111.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `DOMElement` |  | one <w:p> paragraph element |
| `$ns` | `string` |  | the WordprocessingML namespace URI |

**Returns:** `string`

## Called from

- [includes/rfp_docx_parser.php](/code/files/includes/rfp_docx_parser.php.md) - in [rfpExtractDocxText()](/code/functions/rfpExtractDocxText.md)

