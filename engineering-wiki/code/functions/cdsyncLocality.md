# cdsyncLocality()

The locality (town) from `ADR`, which is the part that answers "which site".

ADR has seven components: PO box, extended, street, locality, region,
postcode, country. The fourth is the town.

```php
function cdsyncLocality(array $lines): ?string
```

Defined in [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md), line 239.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$lines` | `array` |  | the card's unfolded lines from `cardDavUnfold()` |

**Returns:** `?string`

## Calls

[cardDavProperty()](/code/functions/cardDavProperty.md), [cardDavUnescapeText()](/code/functions/cardDavUnescapeText.md)

## Called from

- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cdsyncMapCard()](/code/functions/cdsyncMapCard.md)

