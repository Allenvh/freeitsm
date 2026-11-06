# icsFold()

Fold a content line to <=75 octets; continuation lines start with a space.

Byte-based, not character-based, deliberately: the limit in RFC 5545 is
octets, and a subject with an accent or an emoji in it is longer than it
looks. Calendar clients are unforgiving about over-long lines.

```php
function icsFold(string $line): string
```

Defined in [includes/ics.php](/code/files/includes/ics.php.md), line 41.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$line` | `string` |  | one complete content line, e.g. 'SUMMARY:...', already escaped |

**Returns:** `string`

## Called from

- [includes/calendar_sync/CalDavCalendarProvider.php](/code/files/includes/calendar_sync/CalDavCalendarProvider.php.md) - in `CalDavCalendarProvider::rewriteEvent`
- [includes/ics.php](/code/files/includes/ics.php.md) - in [icsHeader()](/code/functions/icsHeader.md), [icsEvent()](/code/functions/icsEvent.md)

