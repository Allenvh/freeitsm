# apiCalendarEventsCreate()

POST /calendar/events

```php
function apiCalendarEventsCreate(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md), line 202.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiFailFromService()](/code/functions/apiFailFromService.md), [apiLoadCalendarEvent()](/code/functions/apiLoadCalendarEvent.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeCalendarEvent()](/code/functions/apiSerializeCalendarEvent.md)

*Nothing calls this function by name.*

