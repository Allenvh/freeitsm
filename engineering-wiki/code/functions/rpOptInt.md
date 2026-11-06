# rpOptInt()

A whole-number block option, between `$min` and `$max`.

```php
function rpOptInt(string $label, int $min, int $max, int $default): array
```

Defined in [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md), line 59.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$label` | `string` |  | the option's label in the Properties pane |
| `$min` | `int` |  | the smallest value allowed |
| `$max` | `int` |  | the largest value allowed |
| `$default` | `int` |  | the value used unless the author picks another |

**Returns:** `array`

## Called from

- [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md) - in [rpHandlers()](/code/functions/rpHandlers.md)

