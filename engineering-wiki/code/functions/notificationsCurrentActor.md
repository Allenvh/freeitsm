# notificationsCurrentActor()

Who caused this? Resolved centrally from the session rather than threaded through 48 dispatch payloads.

This is what makes "never notify me about my own action" possible at all. A
web request is caused by whoever is signed in; a cron run is caused by nobody,
which is correct — a system-generated SLA breach SHOULD reach the assignee
even though the assignee owns the ticket.

Safe after session_start(['read_and_close' => true]): the array stays in
memory for the rest of the request even though the file lock is gone.

```php
function notificationsCurrentActor(): array
```

Defined in [includes/notifications_router.php](/code/files/includes/notifications_router.php.md), line 33.

**Returns:** `array`

## Called from

- [includes/notifications_router.php](/code/files/includes/notifications_router.php.md) - in [notificationsHandleEvent()](/code/functions/notificationsHandleEvent.md)

