# notificationsEntityFor()

Display data for the row: id, human reference, and what it is about.

```php
function notificationsEntityFor(string $event, array $payload, string $entityType): ?array
```

Defined in [includes/notifications_router.php](/code/files/includes/notifications_router.php.md), line 267.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$event` | `string` |  | the workflow event name, used to word domain and project titles |
| `$payload` | `array` |  | the event's dispatch payload, holding the task, domain, project or ticket array |
| `$entityType` | `string` |  | the notification type's entity: 'task', 'domain', 'project', or anything else for a ticket |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 372 | `notificationsEntityFor` | read [tickets](/code/tables/tickets.md) |  |

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md)

## Called from

- [includes/notifications_router.php](/code/files/includes/notifications_router.php.md) - in [notificationsHandleEvent()](/code/functions/notificationsHandleEvent.md)

