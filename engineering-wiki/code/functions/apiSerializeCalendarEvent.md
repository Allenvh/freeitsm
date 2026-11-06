# apiSerializeCalendarEvent()

Shapes a calendar event row for the API - times stay naive server-local, source says whether it was generated (null = typed in), and created_by is null for generated events.

```php
function apiSerializeCalendarEvent(array $r): array
```

Defined in [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md), line 85.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | the calendar_events row as `apiCalendarEventSelect()` loads it, with category and creator names |

**Returns:** `array`

## Called from

- [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md) - in [apiCalendarEventsGet()](/code/functions/apiCalendarEventsGet.md), [apiCalendarEventsCreate()](/code/functions/apiCalendarEventsCreate.md), [apiCalendarEventsUpdate()](/code/functions/apiCalendarEventsUpdate.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::fullObjectLoaders`

