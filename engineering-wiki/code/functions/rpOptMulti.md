# rpOptMulti()

A multi-select block option whose choices come from the named source.

```php
function rpOptMulti(string $label, string $source): array
```

Defined in [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md), line 79.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$label` | `string` |  | the option's label in the Properties pane |
| `$source` | `string` |  | where the choices come from, for `rpOptionSource()`: 'projects' or 'status_services' |

**Returns:** `array`

## Called from

- [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md) - in [rpHandlers()](/code/functions/rpHandlers.md)

