# cardDavRequest()

One HTTP request to a DAV server.

Returns ['ok' => bool, 'status' => int, 'body' => string, 'error' => string,
         'auth' => string] where `auth` names the scheme that succeeded.

```php
function cardDavRequest(
    array $cfg,
    string $method,
    string $url,
    string $body = '',
    array $headers = []
): array
```

Defined in [includes/carddav.php](/code/files/includes/carddav.php.md), line 72.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | url, username, password, auth ('auto'\|'digest'\|'basic') |
| `$method` | `string` |  | PROPFIND, REPORT, GET… |
| `$url` | `string` |  | absolute URL |
| `$body` | `string` | `''` | request body, may be '' |
| `$headers` | `array` | `[]` | extra headers |

**Returns:** `array`

## Calls

[cardDavExplainStatus()](/code/functions/cardDavExplainStatus.md)

## Called from

- [includes/calendar_sync/CalDavCalendarProvider.php](/code/files/includes/calendar_sync/CalDavCalendarProvider.php.md) - in `CalDavCalendarProvider::createEvent`, `CalDavCalendarProvider::updateEvent`, `CalDavCalendarProvider::deleteEvent`, `CalDavCalendarProvider::currentSyncToken`, `CalDavCalendarProvider::pollChangesOnce`, `CalDavCalendarProvider::fetchChanges`, `CalDavCalendarProvider::verifyConnection`, `CalDavCalendarProvider::checkCalendar`, `CalDavCalendarProvider::discoverCalendars`
- [includes/carddav.php](/code/files/includes/carddav.php.md) - in [cardDavListAddressBooks()](/code/functions/cardDavListAddressBooks.md), [cardDavFetchCards()](/code/functions/cardDavFetchCards.md)
- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavPutCard()](/code/functions/cardDavPutCard.md), [cardDavGetCard()](/code/functions/cardDavGetCard.md), [cardDavCanWrite()](/code/functions/cardDavCanWrite.md)

