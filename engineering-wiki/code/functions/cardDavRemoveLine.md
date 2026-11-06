# cardDavRemoveLine()

Remove the logical line at `$index`, its continuations and its line ending.

```php
function cardDavRemoveLine(string $raw, array $spans, int $index): string
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 403.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the card as it stands |
| `$spans` | `array` |  | `cardDavLineSpans()` of that same `$raw` |
| `$index` | `int` |  | which span to remove |

**Returns:** `string`

## Called from

- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavApplyPersonEdits()](/code/functions/cardDavApplyPersonEdits.md)

