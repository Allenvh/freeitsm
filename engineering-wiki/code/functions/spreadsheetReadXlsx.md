# spreadsheetReadXlsx()

The first worksheet of an .xlsx as rows of display strings.

```php
function spreadsheetReadXlsx(string $path): array
```

Defined in [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md), line 259.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | the .xlsx file on disk - untrusted content |

**Returns:** `array`

## Calls

[spreadsheetColIndex()](/code/functions/spreadsheetColIndex.md), [spreadsheetInlineText()](/code/functions/spreadsheetInlineText.md), [spreadsheetXml()](/code/functions/spreadsheetXml.md), [spreadsheetZipGet()](/code/functions/spreadsheetZipGet.md), [spreadsheetZipOpen()](/code/functions/spreadsheetZipOpen.md)

## Called from

- [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md) - in [spreadsheetRead()](/code/functions/spreadsheetRead.md)

