# cardDavInsertLine()

Add a property that the card does not currently have, immediately before `END:VCARD`.

⚠️ Before END:VCARD rather than appended, obviously — but also NOT at the top,
because `BEGIN`, `VERSION` and often `UID` are expected in that order by
stricter parsers and inserting above them has broken clients before.

Returns the card unchanged if there is no END:VCARD to insert before: a card
that malformed is not one to start editing.

```php
function cardDavInsertLine(string $raw, string $logicalLine): string
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 381.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the card as it stands |
| `$logicalLine` | `string` |  | the whole new content line, value already escaped; folded here |

**Returns:** `string`

## Calls

[cardDavDetectEol()](/code/functions/cardDavDetectEol.md), [cardDavFoldLine()](/code/functions/cardDavFoldLine.md), [cardDavLineSpans()](/code/functions/cardDavLineSpans.md)

## Called from

- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavApplyPersonEdits()](/code/functions/cardDavApplyPersonEdits.md)

