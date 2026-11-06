# cardDavFindCardByUid()

Find a card in a fetched book by UID.

```php
function cardDavFindCardByUid(array $cards, string $uid): ?array
```

Defined in [includes/carddav_create.php](/code/files/includes/carddav_create.php.md), line 169.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cards` | `array` |  | the 'cards' list from `cardDavFetchCards()` - each with href, etag, vcard |
| `$uid` | `string` |  | the UID to find, matched exactly |

**Returns:** `?array`

## Calls

[cardDavProperty()](/code/functions/cardDavProperty.md), [cardDavUnfold()](/code/functions/cardDavUnfold.md)

## Called from

- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)

