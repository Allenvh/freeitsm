# spreadsheetColIndex()

"BC12" -> 54 (zero-based column).

```php
function spreadsheetColIndex(string $ref): ?int
```

Defined in [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md), line 345.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ref` | `string` |  | a cell reference such as 'BC12' (any case) |

**Returns:** `?int`

## Called from

- [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md) - in [spreadsheetReadXlsx()](/code/functions/spreadsheetReadXlsx.md)

