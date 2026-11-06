# cardDavParseAddressBooks()

Pull the address books out of a PROPFIND multistatus body.

Returns null when the body is not parseable as DAV at all — which is a
different answer from "parsed fine, found none", and the two must not be
collapsed: the first is a wrong URL, the second is an empty account.

```php
function cardDavParseAddressBooks(string $xml): ?array
```

Defined in [includes/carddav.php](/code/files/includes/carddav.php.md), line 274.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$xml` | `string` |  | the PROPFIND multistatus body as the server sent it |

**Returns:** `?array`

## Called from

- [includes/carddav.php](/code/files/includes/carddav.php.md) - in [cardDavListAddressBooks()](/code/functions/cardDavListAddressBooks.md)

