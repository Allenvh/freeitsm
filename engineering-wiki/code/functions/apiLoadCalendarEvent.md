# apiLoadCalendarEvent()

Loads one calendar event row (apiCalendarEventSelect), or answers 404 and stops.

```php
function apiLoadCalendarEvent(PDO $conn, int $eventId): array
```

Defined in [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md), line 114.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$eventId` | `int` |  | the calendar_events id |

**Returns:** `array`

## Calls

[apiCalendarEventSelect()](/code/functions/apiCalendarEventSelect.md), [apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md) - in [apiCalendarEventsGet()](/code/functions/apiCalendarEventsGet.md), [apiCalendarEventsCreate()](/code/functions/apiCalendarEventsCreate.md), [apiCalendarEventsUpdate()](/code/functions/apiCalendarEventsUpdate.md)

