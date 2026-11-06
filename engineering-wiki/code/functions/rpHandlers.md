# rpHandlers()

Handlers, keyed. `module` gates the data; `kinds` lists what it can return (for the designer's preview); `fn` fetches it; `opts` is the option schema.

```php
function rpHandlers(): array
```

Defined in [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md), line 99.

**Returns:** `array`

## Calls

[rpChartOpt()](/code/functions/rpChartOpt.md), [rpOptBool()](/code/functions/rpOptBool.md), [rpOptInt()](/code/functions/rpOptInt.md), [rpOptMulti()](/code/functions/rpOptMulti.md), [rpOptSelect()](/code/functions/rpOptSelect.md), [t()](/code/functions/t.md)

## Called from

- [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md) - in [rpCleanOpts()](/code/functions/rpCleanOpts.md), [rpBlockData()](/code/functions/rpBlockData.md), [rpCatalogue()](/code/functions/rpCatalogue.md)
- [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md) - in [rpCleanDesign()](/code/functions/rpCleanDesign.md)

