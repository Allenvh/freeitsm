# spreadsheetReadCsv()

CSV in any of the shapes Excel saves: UTF-8 with or without a BOM, or the Windows code page; comma, semicolon (Excel in most of Europe) or tab.

```php
function spreadsheetReadCsv(string $path): array
```

Defined in [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md), line 86.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | the CSV file on disk |

**Returns:** `array`

## Called from

- [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md) - in [spreadsheetRead()](/code/functions/spreadsheetRead.md)

