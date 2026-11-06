# cardDavListAddressBooks()

List the address books an account can see.

One `PROPFIND` with `Depth: 1` against the given URL. A collection that
carries the `{urn:ietf:params:xml:ns:carddav}addressbook` resource type is an
address book; everything else in the response — including the container
itself — is not, which is how the parent gets excluded without special-casing
it by URL.

```php
function cardDavListAddressBooks(array $cfg): array
```

Defined in [includes/carddav.php](/code/files/includes/carddav.php.md), line 225.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | request settings from `cardDavConfigFromProvider()` - url (the account's DAV home), username, password, auth |

**Returns:** array ['ok'=>bool, 'books'=>[['href'=>…,'name'=>…,'description'=>…]], 'auth'=>…, 'error'=>…, 'status'=>int]

## Calls

[cardDavParseAddressBooks()](/code/functions/cardDavParseAddressBooks.md), [cardDavRequest()](/code/functions/cardDavRequest.md)

## Called from

- [api/system/debug-tools/D015_carddav_health.php](/code/files/api/system/debug-tools/D015_carddav_health.php.md)
- [api/system/test_carddav_connection.php](/code/files/api/system/test_carddav_connection.php.md)

