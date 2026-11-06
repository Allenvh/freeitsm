# spreadsheetXml()

Parses one XML part of an .xlsx file safely (no network, no entity expansion); null when empty or not valid XML.

```php
function spreadsheetXml(?string $xml)
```

Defined in [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md), line 373.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$xml` | `?string` |  | one XML part's bytes from the workbook - untrusted; null or '' gives null |

## Called from

- [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md) - in [spreadsheetReadXlsx()](/code/functions/spreadsheetReadXlsx.md)

