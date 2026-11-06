# rpPara()

A paragraph of plain text, for the starter design.

```php
function rpPara(string $text, array $extra = []): array
```

Defined in [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md), line 44.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$text` | `string` |  | the paragraph's text |
| `$extra` | `array` | `[]` | paragraph keys that override the defaults, e.g. ['a' => 'center', 'r' => [...]] |

**Returns:** `array`

## Called from

- [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md) - in [rpDefaultDesign()](/code/functions/rpDefaultDesign.md)

