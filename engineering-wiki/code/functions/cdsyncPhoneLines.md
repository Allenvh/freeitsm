# cdsyncPhoneLines()

WHICH lines the two numbers came from, as indexes into the unfolded card.

🔴 Split out from `cdsyncPhones()` so that reading and writing cannot disagree.
A card routinely carries three or four `TEL` lines, and "the mobile" is not a
property name but the result of a selection rule — first CELL, else first
WORK, else the first untyped one. If the writer re-implemented that rule and
got it even slightly different, editing somebody's mobile would overwrite
their desk number and leave the mobile untouched, with no error anywhere.
One selector, used by both ends, makes that class of bug unrepresentable.

```php
function cdsyncPhoneLines(array $lines): array
```

Defined in [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md), line 177.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$lines` | `array` |  | the card's unfolded lines from `cardDavUnfold()` |

**Returns:** array ['phone' => int|null, 'mobile' => int|null] indexes into `$lines`

## Called from

- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cdsyncPhones()](/code/functions/cdsyncPhones.md)
- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavLocateField()](/code/functions/cardDavLocateField.md)

