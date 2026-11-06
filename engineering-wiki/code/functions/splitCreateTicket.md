# splitCreateTicket()

Create the ticket the messages move into.

Inherits the requester, company, department and type from the source — a split is
the same person and the same context, just a different problem. Priority and
assignee are deliberately NOT inherited... except the assignee is, because whoever
is holding the conversation should keep holding both halves until they decide
otherwise. Status starts at the install's first OPEN status: the new work is by
definition unfinished, even if the original had been resolved.

```php
function splitCreateTicket(
    PDO $conn,
    array $source,
    array $moving,
    int $actorId,
    ?string $newSubject
): int
```

Defined in [includes/ticket_split.php](/code/files/includes/ticket_split.php.md), line 262.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$source` | `array` |  | the source ticket row as loaded by TicketsService::loadTicket() |
| `$moving` | `array` |  | the message rows being moved, oldest first (from splitMessagesFrom/ByIds) |
| `$actorId` | `int` |  | the analyst splitting (not stored on the ticket itself) |
| `$newSubject` | `?string` |  | the subject the analyst typed; null or '' falls back as described above |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 282 | `splitCreateTicket` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 287 | `splitCreateTicket` | **insert** [tickets](/code/tables/tickets.md) (`ticket_number`, `subject`, `status_id`, `priority_id`, `department_id`, `ticket_type_id`, `assigned_analyst_id`, `origin_id`, `user_id`, `owner_id`, `tenant_id`, `created_datetime`) |  |

## Called from

- [includes/ticket_split.php](/code/files/includes/ticket_split.php.md) - in [splitTicket()](/code/functions/splitTicket.md)

## Traps

> [!WARNING]
> **Line 263, in `splitCreateTicket`:** the install's numbering, never a number made here - see `messagingGenerateTicketNumber()` for what went wrong when it was.

