# apiCalendarEventsList()

GET /calendar/events

```php
function apiCalendarEventsList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md), line 127.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 180 | `apiCalendarEventsList` | read [calendar_events](/code/tables/calendar_events.md) |  |

## Calls

[apiCalendarEventSelect()](/code/functions/apiCalendarEventSelect.md), [apiError()](/code/functions/apiError.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseNaiveDatetime()](/code/functions/apiParseNaiveDatetime.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*

