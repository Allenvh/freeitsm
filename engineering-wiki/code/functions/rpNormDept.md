# rpNormDept()

A department name normalised for matching: spaces collapsed, trimmed and lower case.

```php
function rpNormDept($s): string
```

Defined in [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md), line 61.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` |  |  | a department name as typed or stored; null gives '' |

**Returns:** `string`

## Called from

- [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md) - in [rpViewer()](/code/functions/rpViewer.md), [rpSaveShares()](/code/functions/rpSaveShares.md), [rpKnownDepartments()](/code/functions/rpKnownDepartments.md)

