# spreadsheetZipGet()

One entry's bytes, or null when it is not there.

```php
function spreadsheetZipGet(array &$zip, string $name): ?string
```

Defined in [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md), line 418.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$zip` | `array` |  | the open zip from `spreadsheetZipOpen()`, by reference - its running inflated total is updated |
| `$name` | `string` |  | the entry's path inside the zip, e.g. 'xl/workbook.xml' |

**Returns:** `?string`

## Called from

- [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md) - in [spreadsheetReadXlsx()](/code/functions/spreadsheetReadXlsx.md)

