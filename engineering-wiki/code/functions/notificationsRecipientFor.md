# notificationsRecipientFor()

The ONE person an event is about, for events that are about one person.

Kept separate from the audience above because these two answer different
questions: this one is "whose news is this?", and it is also what the
ticket events — which have no list of involved people — still use.

```php
function notificationsRecipientFor(string $event, array $payload): int
```

Defined in [includes/notifications_router.php](/code/files/includes/notifications_router.php.md), line 220.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$event` | `string` |  | the workflow event name, e.g. 'ticket.assigned' |
| `$payload` | `array` |  | the event's dispatch payload - reads analyst_id, ticket, task, domain or project from it |

**Returns:** `int`

## Called from

- [includes/notifications_router.php](/code/files/includes/notifications_router.php.md) - in [notificationsAudienceFor()](/code/functions/notificationsAudienceFor.md)

