# cardDavFetchCards()

Fetch every vCard in one address book, in a single request.

An `addressbook-query` REPORT with `address-data` returns the cards inline,
so this is one round trip rather than a PROPFIND followed by a GET per card —
which on an address book of a few thousand contacts is the difference between
one request and a few thousand.

```php
function cardDavFetchCards(array $cfg, string $bookHref): array
```

Defined in [includes/carddav.php](/code/files/includes/carddav.php.md), line 335.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | request settings from `cardDavConfigFromProvider()` |
| `$bookHref` | `string` |  | the address book's href as the server listed it, absolute or relative to `$cfg`['url'] |

**Returns:** array ['ok'=>bool, 'cards'=>[['href'=>…,'etag'=>…,'vcard'=>…]], 'error'=>…, 'status'=>int]

## Calls

[cardDavAbsoluteUrl()](/code/functions/cardDavAbsoluteUrl.md), [cardDavRequest()](/code/functions/cardDavRequest.md)

## Called from

- [api/system/debug-tools/D016_carddav_drift.php](/code/files/api/system/debug-tools/D016_carddav_drift.php.md)
- [includes/carddav.php](/code/files/includes/carddav.php.md) - in [cardDavScanBook()](/code/functions/cardDavScanBook.md)
- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cardDavSyncRun()](/code/functions/cardDavSyncRun.md)

