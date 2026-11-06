# apiCalendarEventSelect()

The SELECT for calendar events, with the category's name and colour and the creator's name.

```php
function apiCalendarEventSelect(): string
```

Defined in [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md), line 70.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 71 | `apiCalendarEventSelect` | read [calendar_events](/code/tables/calendar_events.md), read [calendar_categories](/code/tables/calendar_categories.md), read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md) - in [apiLoadCalendarEvent()](/code/functions/apiLoadCalendarEvent.md), [apiCalendarEventsList()](/code/functions/apiCalendarEventsList.md)

