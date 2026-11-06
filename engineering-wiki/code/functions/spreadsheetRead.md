# spreadsheetRead()

Read an uploaded CSV or .xlsx into rows of strings (the first sheet of a workbook). Every value is a string exactly as the person sees it, trimmed. Blank rows are dropped.

```php
function spreadsheetRead(string $path, string $originalName): array
```

Defined in [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md), line 40.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | the uploaded file on disk (the upload's tmp_name) - untrusted content |
| `$originalName` | `string` |  | the file name the person uploaded; only its extension is read, to spot an old .xls |

**Returns:** array<int, array<int, string>>

## Calls

[spreadsheetCleanText()](/code/functions/spreadsheetCleanText.md), [spreadsheetReadCsv()](/code/functions/spreadsheetReadCsv.md), [spreadsheetReadXlsx()](/code/functions/spreadsheetReadXlsx.md)

## Called from

- [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md)

