# cardDavProperty()

Read one property's values out of an unfolded vCard.

⚠️ Matches on the property NAME before any parameters: `CATEGORIES:a,b` and
`CATEGORIES;TYPE=x:a,b` are the same property, and an implementation that
looks for `CATEGORIES:` misses the second. Comparison is case-insensitive
because the spec says property names are.

```php
function cardDavProperty(array $lines, string $name): array
```

Defined in [includes/carddav.php](/code/files/includes/carddav.php.md), line 500.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$lines` | `array` |  | the card's unfolded lines from `cardDavUnfold()` |
| `$name` | `string` |  | the property name to find, e.g. 'CATEGORIES' - any case |

**Returns:** `array`

## Called from

- [includes/carddav.php](/code/files/includes/carddav.php.md) - in [cardDavScanBook()](/code/functions/cardDavScanBook.md)
- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavFindCardByUid()](/code/functions/cardDavFindCardByUid.md), [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cdsyncMapCard()](/code/functions/cdsyncMapCard.md), [cdsyncOrg()](/code/functions/cdsyncOrg.md), [cdsyncLocality()](/code/functions/cdsyncLocality.md), [cdsyncResolveScope()](/code/functions/cdsyncResolveScope.md)

