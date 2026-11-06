# cardDavApplyPersonEdits()

Apply a set of FreeITSM field changes to a card, surgically.

```php
function cardDavApplyPersonEdits(string $raw, array $changes): array
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 506.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the card exactly as the server returned it |
| `$changes` | `array` |  | field => new plain value (null or '' clears it) |

**Returns:** array ['card' => string, 'changed' => string[], 'skipped' => array] `changed` names the fields actually written; `skipped` maps a field to why it was not.

## Calls

[cardDavEscapeText()](/code/functions/cardDavEscapeText.md), [cardDavInsertLine()](/code/functions/cardDavInsertLine.md), [cardDavLineSpans()](/code/functions/cardDavLineSpans.md), [cardDavLocateField()](/code/functions/cardDavLocateField.md), [cardDavRemoveLine()](/code/functions/cardDavRemoveLine.md), [cardDavReplaceLineValue()](/code/functions/cardDavReplaceLineValue.md), [cardDavSetComponent()](/code/functions/cardDavSetComponent.md), [cardDavUnescapeText()](/code/functions/cardDavUnescapeText.md), [cardDavWritableFields()](/code/functions/cardDavWritableFields.md)

## Called from

- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavPushPersonChanges()](/code/functions/cardDavPushPersonChanges.md)

