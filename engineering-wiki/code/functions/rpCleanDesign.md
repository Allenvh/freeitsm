# rpCleanDesign()

Check a whole design from the browser. Returns the cleaned design; throws InvalidArgumentException when it is not a design at all or is too large.

```php
function rpCleanDesign($d, string $name): array
```

Defined in [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md), line 193.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$d` |  |  | the whole design from the browser (untrusted) |
| `$name` | `string` |  | the pack's name, for the defaults the design falls back on |

**Returns:** `array`

## Calls

[rpCleanDoc()](/code/functions/rpCleanDoc.md), [rpCleanOpts()](/code/functions/rpCleanOpts.md), [rpColour()](/code/functions/rpColour.md), [rpDefaultDesign()](/code/functions/rpDefaultDesign.md), [rpHandlers()](/code/functions/rpHandlers.md), [rpNum()](/code/functions/rpNum.md), [rpPick()](/code/functions/rpPick.md), [rpStr()](/code/functions/rpStr.md), [t()](/code/functions/t.md)

## Called from

- [api/reporting/packs/create.php](/code/files/api/reporting/packs/create.php.md)
- [api/reporting/packs/get.php](/code/files/api/reporting/packs/get.php.md)
- [api/reporting/packs/save.php](/code/files/api/reporting/packs/save.php.md)

