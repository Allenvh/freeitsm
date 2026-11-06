# spreadsheetWriteCsv()

CSV bytes: UTF-8 with a BOM (so Excel reads accents correctly), CRLF.

A cell that starts with = + - @ is prefixed with ' so a spreadsheet does not
run it as a formula ("CSV injection"); the reader takes the ' off again. A
plain negative number is left alone.

```php
function spreadsheetWriteCsv(array $header, array $rows): string
```

Defined in [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md), line 132.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$header` | `array` |  | the column headings, in order |
| `$rows` | `array` |  | the data rows, each a list of cell values in column order |

**Returns:** `string`

## Called from

- [api/assets/leases/due_back.php](/code/files/api/assets/leases/due_back.php.md)
- [api/projects/export.php](/code/files/api/projects/export.php.md)
- [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md)

