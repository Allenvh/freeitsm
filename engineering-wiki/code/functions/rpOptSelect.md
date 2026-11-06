# rpOptSelect()

Option helpers, so the handler table below stays readable.

```php
function rpOptSelect(string $label, array $values, string $default): array
```

Defined in [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md), line 47.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$label` | `string` |  | the option's label in the Properties pane |
| `$values` | `array` |  | the choices, value => label |
| `$default` | `string` |  | the value chosen unless the author picks another |

**Returns:** `array`

## Called from

- [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md) - in [rpChartOpt()](/code/functions/rpChartOpt.md), [rpHandlers()](/code/functions/rpHandlers.md)

