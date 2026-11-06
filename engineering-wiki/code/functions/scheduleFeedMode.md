# scheduleFeedMode()

The install-wide ceiling on subscribe links.

Defaults to 'full' — permissive — because nothing is actually published until
an analyst deliberately mints a link for themselves. An upgrade therefore
exposes nothing on its own, and an administrator who wants the feature gone
turns it off without anyone having had to opt in first.

```php
function scheduleFeedMode(PDO $conn): string
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 387.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 393 | `scheduleFeedMode` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md)
- [api/tickets/get_schedule_feed_url.php](/code/files/api/tickets/get_schedule_feed_url.php.md)
- [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) - in [scheduleFeedAllowed()](/code/functions/scheduleFeedAllowed.md), [scheduleFeedDetail()](/code/functions/scheduleFeedDetail.md)

