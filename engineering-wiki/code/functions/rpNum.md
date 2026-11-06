# rpNum()

A number clamped between `$min` and `$max`, or `$default` when the value is not numeric.

```php
function rpNum($v, float $min, float $max, float $default): float
```

Defined in [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md), line 109.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the value to check (untrusted) |
| `$min` | `float` |  | the smallest value allowed |
| `$max` | `float` |  | the largest value allowed |
| `$default` | `float` |  | what to return when it is not numeric |

**Returns:** `float`

## Called from

- [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md) - in [rpCleanDoc()](/code/functions/rpCleanDoc.md), [rpCleanDesign()](/code/functions/rpCleanDesign.md)

