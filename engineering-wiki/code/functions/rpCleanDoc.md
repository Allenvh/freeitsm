# rpCleanDoc()

Check a rich-text document. Anything unrecognised is dropped, not kept.

```php
function rpCleanDoc($doc, int &$budget): array
```

Defined in [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md), line 146.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$doc` |  |  | the rich-text document from the browser (untrusted): a list of paragraphs |
| `$budget` | `int` |  | paragraphs still allowed across the whole design; counted down as each is read |

**Returns:** `array`

## Calls

[rpColour()](/code/functions/rpColour.md), [rpNum()](/code/functions/rpNum.md), [rpPick()](/code/functions/rpPick.md), [rpStr()](/code/functions/rpStr.md)

## Called from

- [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md) - in [rpCleanDesign()](/code/functions/rpCleanDesign.md)

