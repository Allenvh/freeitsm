# cdsyncPhones()

Split a card's telephone numbers into a phone and a mobile.

⚠️ `TEL;TYPE=CELL` and `TEL;TYPE="voice,cell"` and `TEL;TYPE=cell;TYPE=voice`
are all legal ways to say the same thing, so the whole parameter string is
searched for the word rather than compared to it.

```php
function cdsyncPhones(array $lines): array
```

Defined in [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md), line 149.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$lines` | `array` |  | the card's unfolded lines from `cardDavUnfold()` |

**Returns:** `array`

## Calls

[cardDavUnescapeText()](/code/functions/cardDavUnescapeText.md), [cdsyncPhoneLines()](/code/functions/cdsyncPhoneLines.md)

## Called from

- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cdsyncMapCard()](/code/functions/cdsyncMapCard.md)

