# feed_deny()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/calendar/feed.php](/code/files/api/calendar/feed.php.md), [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md)

Sends an HTTP status and a plain-text message, then stops - the feed's only error path, since a calendar app shows nothing richer.

```php
function feed_deny($code, $msg)
```

Defined in [api/calendar/feed.php](/code/files/api/calendar/feed.php.md), line 25.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$code` |  |  | the HTTP status line after the protocol, e.g. '404 Not Found' |
| `$msg` |  |  | the plain-text body the calendar app receives |

## Called from

- [api/calendar/feed.php](/code/files/api/calendar/feed.php.md)
- [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md)

