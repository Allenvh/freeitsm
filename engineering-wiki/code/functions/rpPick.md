# rpPick()

The value when it is one of `$allowed`, else `$default`.

```php
function rpPick($v, array $allowed, string $default): string
```

Defined in [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md), line 135.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the value to check (untrusted) |
| `$allowed` | `array` |  | the values accepted |
| `$default` | `string` |  | what to return when it is not one of them |

**Returns:** `string`

## Called from

- [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md) - in [rpCleanDoc()](/code/functions/rpCleanDoc.md), [rpCleanDesign()](/code/functions/rpCleanDesign.md)

