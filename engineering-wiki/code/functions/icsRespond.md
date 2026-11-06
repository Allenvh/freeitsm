# icsRespond()

Emit the finished calendar with the right headers.

```php
function icsRespond(array $lines, string $filename): void
```

Defined in [includes/ics.php](/code/files/includes/ics.php.md), line 156.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$lines` | `array` |  | every line so far - `icsHeader()` then `icsEvent()` output; END:VCALENDAR is added here |
| `$filename` | `string` |  | the file name for Content-Disposition, e.g. 'calendar.ics' |

**Returns:** `void`

## Called from

- [api/calendar/feed.php](/code/files/api/calendar/feed.php.md)
- [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md)

