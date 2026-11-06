# spreadsheetZipStore()

A zip with every entry STORED (no compression) - valid, and all Excel needs.

```php
function spreadsheetZipStore(array $files): string
```

Defined in [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md), line 232.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$files` | `array` |  | [path inside the zip => file bytes], written in that order |

**Returns:** `string`

## Called from

- [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md) - in [spreadsheetWriteXlsx()](/code/functions/spreadsheetWriteXlsx.md)

