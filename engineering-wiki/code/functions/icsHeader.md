# icsHeader()

The VCALENDAR header lines. `$name` is what the client shows as the calendar's name.

```php
function icsHeader(string $name, string $tz, int $refreshHours = 6): array
```

Defined in [includes/ics.php](/code/files/includes/ics.php.md), line 62.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | the calendar's display name (X-WR-CALNAME) |
| `$tz` | `string` |  | the feed's IANA time zone name, e.g. 'Europe/London' (X-WR-TIMEZONE) |
| `$refreshHours` | `int` | `6` | how often clients are asked to refresh, in hours - only a hint |

**Returns:** `array`

## Calls

[icsEscape()](/code/functions/icsEscape.md), [icsFold()](/code/functions/icsFold.md)

## Called from

- [api/calendar/feed.php](/code/files/api/calendar/feed.php.md)
- [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md)

