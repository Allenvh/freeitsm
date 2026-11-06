# notificationsBodyFor()

The one-line description.

Kept as translatable keys resolved at render time rather than baked English in
the database — otherwise a row written today reads in whatever language the
writer happened to be using, forever.

```php
function notificationsBodyFor(string $event, ?string $actorName): string
```

Defined in [includes/notifications_router.php](/code/files/includes/notifications_router.php.md), line 395.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$event` | `string` |  | the workflow event name (not used - the bell picks the wording by event at render time) |
| `$actorName` | `?string` |  | the display name of whoever caused the event; null when nobody did (e.g. cron) |

**Returns:** `string`

## Called from

- [includes/notifications_router.php](/code/files/includes/notifications_router.php.md) - in [notificationsHandleEvent()](/code/functions/notificationsHandleEvent.md)

