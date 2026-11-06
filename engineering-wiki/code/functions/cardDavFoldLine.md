# cardDavFoldLine()

Fold one logical line the way RFC 6350 §3.2 asks: break at 75 octets and begin each continuation with a single space.

🔴 UTF-8 AWARE, and it has to be. The limit is defined in OCTETS, not
characters, so a naive `substr(`$s`, 0, 75)` will happily cut a multi-byte
character in half — and the two halves then sit either side of a fold, so the
receiving parser rejoins them into a byte sequence that is not valid UTF-8.
A Polish or Danish address book, which FreeITSM has real users running, hits
this on ordinary data rather than as an edge case.

⚠️ Folds on a CONTINUATION BYTE boundary (`10xxxxxx`), walking back at most
three bytes, so a character is never split.

```php
function cardDavFoldLine(string $logical, string $eol = "\r\n"): string
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 231.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$logical` | `string` |  | one unfolded content line |
| `$eol` | `string` | `"\r\n"` | the line ending to fold with - the card's own, from `cardDavDetectEol()` |

**Returns:** `string`

## Called from

- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavReplaceLineValue()](/code/functions/cardDavReplaceLineValue.md), [cardDavInsertLine()](/code/functions/cardDavInsertLine.md)

