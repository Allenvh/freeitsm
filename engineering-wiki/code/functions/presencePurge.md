# presencePurge()

Bin rows nobody will ever read again.

Ridden on the back of the heartbeat rather than given a cron, because it is
one indexed DELETE and the feature already depends on someone heartbeating —
if nothing is beating, nothing is accumulating either. Stale rows are already
invisible (presenceOthers filters on time), so this is housekeeping and not
correctness, and the cutoff is deliberately far beyond the stale window.

```php
function presencePurge(PDO $conn): void
```

Defined in [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md), line 153.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 156 | `presencePurge` | **delete** [ticket_presence](/code/tables/ticket_presence.md) |  |

## Calls

[presenceSchemaReady()](/code/functions/presenceSchemaReady.md)

## Called from

- [api/tickets/ticket_presence.php](/code/files/api/tickets/ticket_presence.php.md)

