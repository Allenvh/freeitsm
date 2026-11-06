# cardDavReplaceLineValue()

Replace the VALUE of the logical line at `$index`, keeping its name, its group prefix and every one of its parameters exactly as they were.

🔑 The parameters are the point. A line is `TEL;TYPE=WORK;X-ABLabel=Reception:
0123` and only the bit after the colon is ours to change — rewriting the whole
line from a property name and a value would quietly drop the label, the type,
and any vendor parameter that made the entry meaningful in the client that
created it.

```php
function cardDavReplaceLineValue(string $raw, array $spans, int $index, string $newValue): string
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 354.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the card as it stands |
| `$spans` | `array` |  | `cardDavLineSpans()` of that same `$raw` |
| `$index` | `int` |  | which span's value to replace |
| `$newValue` | `string` |  | ALREADY ESCAPED — this function does not escape, so that a structured value assembled from components (each escaped separately) can be passed through whole. |

**Returns:** `string`

## Calls

[cardDavDetectEol()](/code/functions/cardDavDetectEol.md), [cardDavFoldLine()](/code/functions/cardDavFoldLine.md)

## Called from

- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavApplyPersonEdits()](/code/functions/cardDavApplyPersonEdits.md)

