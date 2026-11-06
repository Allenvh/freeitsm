# cardDavDetectEol()

Which line ending does this card use?

⚠️ Asked rather than assumed. The spec says CRLF, and most servers comply, but
plenty of cards in the wild are stored LF-only — and a file that mixes the two
after an edit is the kind of thing that parses fine everywhere except the one
client the operator actually uses.

```php
function cardDavDetectEol(string $raw): string
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 268.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the card exactly as the server returned it |

**Returns:** `string`

## Called from

- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavReplaceLineValue()](/code/functions/cardDavReplaceLineValue.md), [cardDavInsertLine()](/code/functions/cardDavInsertLine.md)

