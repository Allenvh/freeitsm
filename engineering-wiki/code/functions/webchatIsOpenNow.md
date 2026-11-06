# webchatIsOpenNow()

Is the widget "open" right now? A widget with no business-hours calendar (NULL) is always open. Otherwise the calendar's weekday hours + holidays are evaluated in the calendar's own timezone. A misconfigured calendar (missing, bad tz, no hours rows) fails open — availability should never hard-block a visitor from making contact.

```php
function webchatIsOpenNow(PDO $conn, ?int $calendarId): bool
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 392.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$calendarId` | `?int` |  | the widget's business-hours calendar (SLA calendar) id; null means always open |

**Returns:** `bool`

## Calls

[sla_load_calendar()](/code/functions/sla_load_calendar.md)

## Called from

- [api/webchat/config.php](/code/files/api/webchat/config.php.md)
- [api/webchat/send.php](/code/files/api/webchat/send.php.md)

