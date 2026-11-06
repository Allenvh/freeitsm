# rpStr()

A string cut to `$max` characters, with control and zero-width characters removed.

```php
function rpStr($v, int $max): string
```

Defined in [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md), line 119.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the value to clean (untrusted); a number is turned into text, anything else gives '' |
| `$max` | `int` |  | the most characters to keep |

**Returns:** `string`

## Called from

- [api/reporting/packs/create.php](/code/files/api/reporting/packs/create.php.md)
- [api/reporting/packs/save.php](/code/files/api/reporting/packs/save.php.md)
- [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md) - in [rpCleanDoc()](/code/functions/rpCleanDoc.md), [rpCleanDesign()](/code/functions/rpCleanDesign.md)

