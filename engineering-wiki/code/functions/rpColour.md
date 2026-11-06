# rpColour()

A #rrggbb colour in lower case, or `$default` when the value is not one.

```php
function rpColour($v, ?string $default): ?string
```

Defined in [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md), line 97.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the value to check (untrusted) |
| `$default` | `?string` |  | what to return when it is not a #rrggbb colour; may be null |

**Returns:** `?string`

## Called from

- [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md) - in [rpCleanDoc()](/code/functions/rpCleanDoc.md), [rpCleanDesign()](/code/functions/rpCleanDesign.md)

