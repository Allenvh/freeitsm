# cardDavUnfold()

Unfold a vCard into logical lines.

🔴 NOT OPTIONAL. RFC 6350 says a line longer than 75 octets is folded by
inserting CRLF followed by a single space or tab, and servers do it — a long
`CATEGORIES` list is exactly the kind of property that gets folded. Parsing
the raw text line by line therefore reads `CATEGORIES:customer,supplier,pa`
and then a separate line ` rtner`, so the last value in a long list silently
goes missing and everything still looks like it worked.

```php
function cardDavUnfold(string $vcard): array
```

Defined in [includes/carddav.php](/code/files/includes/carddav.php.md), line 419.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$vcard` | `string` |  | one raw vCard as the server sent it, any line endings |

**Returns:** `array`

## Called from

- [includes/carddav.php](/code/files/includes/carddav.php.md) - in [cardDavScanBook()](/code/functions/cardDavScanBook.md)
- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavFindCardByUid()](/code/functions/cardDavFindCardByUid.md), [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cdsyncMapCard()](/code/functions/cdsyncMapCard.md), [cdsyncResolveScope()](/code/functions/cdsyncResolveScope.md)

