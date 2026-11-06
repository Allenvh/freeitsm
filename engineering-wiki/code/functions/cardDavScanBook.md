# cardDavScanBook()

What can this address book be scoped BY?

Reads every card once and reports the two things somebody might want to
filter on, so the settings screen can offer a list instead of a text box:

  - groups     — `KIND:group` cards (how Apple Contacts stores a group), each
                 with how many MEMBERs it names
  - categories — every distinct `CATEGORIES` value across the cards, with a
                 count (how Thunderbird and many Android clients do it)

🔑 Both are reported, always, because a server can use either or both and the
operator is the only one who knows which of their groups is the real one.
`contacts` is the count of ordinary (non-group) cards, which is what "bring
in everything" would import.

```php
function cardDavScanBook(array $cfg, string $bookHref): array
```

Defined in [includes/carddav.php](/code/files/includes/carddav.php.md), line 536.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | request settings from `cardDavConfigFromProvider()` |
| `$bookHref` | `string` |  | the address book's href as the server listed it |

**Returns:** `array`

## Calls

[cardDavFetchCards()](/code/functions/cardDavFetchCards.md), [cardDavProperty()](/code/functions/cardDavProperty.md), [cardDavUnfold()](/code/functions/cardDavUnfold.md)

## Called from

- [api/system/debug-tools/D015_carddav_health.php](/code/files/api/system/debug-tools/D015_carddav_health.php.md)
- [api/system/test_carddav_connection.php](/code/files/api/system/test_carddav_connection.php.md)

