# icsEvent()

One VEVENT.

]

```php
function icsEvent(array $ev, string $tz): array
```

Defined in [includes/ics.php](/code/files/includes/ics.php.md), line 100.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ev` | `array` |  | [ 'uid'         => 'ticket-635@host',      // required, stable across refreshes 'summary'     => 'TICKET-000105 — …',    // required 'description' => '…',                    // optional 'location'    => '…',                    // optional 'categories'  => '…',                    // optional 'url'         => 'https://…',            // optional 'start'       => '2026-09-01 14:00:00',  // naive wall clock, in `$tz` 'end'         => '2026-09-01 16:00:00', 'all_day'     => false, 'stamp'       => 'Y-m-d H:i:s' \| null,   // last-modified; defaults to now |
| `$tz` | `string` |  | the IANA time zone name that start and end are written in, e.g. 'Europe/London' |

**Returns:** string[] lines, or [] when the row cannot be parsed (skipped, never fatal: one bad row must not take the whole feed down)

## Calls

[icsEscape()](/code/functions/icsEscape.md), [icsFold()](/code/functions/icsFold.md)

## Called from

- [api/calendar/feed.php](/code/files/api/calendar/feed.php.md)
- [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md)
- [includes/calendar_sync/CalDavCalendarProvider.php](/code/files/includes/calendar_sync/CalDavCalendarProvider.php.md) - in `CalDavCalendarProvider::eventLines`

