# spreadsheetCleanText()

Strip control characters (bar tab) - a pasted line break must not reach the database.

```php
function spreadsheetCleanText(string $v): string
```

Defined in [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md), line 71.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` | `string` |  | one cell's text |

**Returns:** `string`

## Called from

- [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md) - in [spreadsheetRead()](/code/functions/spreadsheetRead.md), [spreadsheetWriteXlsx()](/code/functions/spreadsheetWriteXlsx.md)

