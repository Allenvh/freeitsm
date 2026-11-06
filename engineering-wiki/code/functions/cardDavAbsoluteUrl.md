# cardDavAbsoluteUrl()

Resolve an href from a DAV response against the configured base URL.

⚠️ A server may answer with an absolute URL, a root-relative path, or a bare
segment, and all three are legal. Concatenating blindly produces
`https://host/dav.php/addressbooks/jsmith//dav.php/addressbooks/jsmith/itsm/`
— a 404 that looks like a missing address book rather than a client bug.

```php
function cardDavAbsoluteUrl(string $baseUrl, string $href): string
```

Defined in [includes/carddav.php](/code/files/includes/carddav.php.md), line 393.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$baseUrl` | `string` |  | the absolute URL the href is relative to (the configured server address) |
| `$href` | `string` |  | an href from a DAV response - absolute, root-relative or a bare segment; '' gives `$baseUrl` |

**Returns:** `string`

## Called from

- [includes/calendar_sync/CalDavCalendarProvider.php](/code/files/includes/calendar_sync/CalDavCalendarProvider.php.md) - in `CalDavCalendarProvider::discoverCalendars`
- [includes/carddav.php](/code/files/includes/carddav.php.md) - in [cardDavFetchCards()](/code/functions/cardDavFetchCards.md)
- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavCanWrite()](/code/functions/cardDavCanWrite.md), [cardDavPushPersonChanges()](/code/functions/cardDavPushPersonChanges.md)

