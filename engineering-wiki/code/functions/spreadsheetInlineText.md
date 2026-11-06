# spreadsheetInlineText()

The text of an <si> or <is>: plain <t>, or rich-text runs <r><t>. Phonetic runs are skipped.

```php
function spreadsheetInlineText($node): string
```

Defined in [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md), line 358.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$node` |  |  | a SimpleXMLElement for an <si> or <is> element, or null |

**Returns:** `string`

## Called from

- [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md) - in [spreadsheetReadXlsx()](/code/functions/spreadsheetReadXlsx.md)

