# rpDefaultDesign()

The design a new pack starts from: a header like Enrique's, a footer, one heading.

```php
function rpDefaultDesign(string $name): array
```

Defined in [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md), line 54.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | the pack's name, shown in the starter header |

**Returns:** `array`

## Calls

[rpPara()](/code/functions/rpPara.md), [t()](/code/functions/t.md)

## Called from

- [api/reporting/packs/create.php](/code/files/api/reporting/packs/create.php.md)
- [api/reporting/packs/get.php](/code/files/api/reporting/packs/get.php.md)
- [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md) - in [rpCleanDesign()](/code/functions/rpCleanDesign.md)

