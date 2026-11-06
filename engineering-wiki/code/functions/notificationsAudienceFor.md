# notificationsAudienceFor()

EVERYBODY who gets told about this event.

⭐ THE "WATCH/FOLLOW TABLE" THIS FILE PREDICTED NOW EXISTS. The note below used
to say the assignee was the only recipient, that "everyone who ever touched it"
was rejected because it is impossible to switch off, and that a watch/follow
table would be the answer — with this function the only place needing to
change. GH #89 built exactly that table for tasks: `task_collaborators` is a
DELIBERATE list of people, put there by hand, and removable. So the task events
that describe the task itself now reach the owner AND everybody involved.

🔴 THE DISTINCTION THAT KEEPS IT FROM BECOMING A FIREHOSE: an event about ONE
PERSON'S place on a task goes to that person alone. Being handed a task, or
being put on one, is news for the individual — sending "a task was assigned to
me" to four other people would be both noisy and false. Events about the TASK —
a comment, a status move, the due date, completion — go to everyone on it.

Tickets are unchanged and still notify the assignee alone: there is no
equivalent list of people on a ticket, and inventing one from "who has touched
it" is the firehose that was rejected.

```php
function notificationsAudienceFor(PDO $conn, string $event, array $payload): array
```

Defined in [includes/notifications_router.php](/code/files/includes/notifications_router.php.md), line 140.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$event` | `string` |  | the workflow event name, e.g. 'task.comment_added' or 'ticket.team_assigned' |
| `$payload` | `array` |  | the event's dispatch payload - reads notify_ids, task, team_id, ticket, domain or project from it |

**Returns:** array<int> analyst ids, de-duplicated. Empty when nobody can be named.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 165 | `notificationsAudienceFor` | read [task_collaborators](/code/tables/task_collaborators.md) |  |
| 197 | `notificationsAudienceFor` | read [analyst_teams](/code/tables/analyst_teams.md) |  |

## Calls

[notificationsRecipientFor()](/code/functions/notificationsRecipientFor.md)

## Called from

- [includes/notifications_router.php](/code/files/includes/notifications_router.php.md) - in [notificationsHandleEvent()](/code/functions/notificationsHandleEvent.md)

