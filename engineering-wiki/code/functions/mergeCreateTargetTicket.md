# mergeCreateTargetTicket()

Create the brand-new ticket that everything folds into ('new' reference mode).

```php
function mergeCreateTargetTicket(PDO $conn, array $model, int $actorId): int
```

Defined in [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), line 445.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$model` | `array` |  | the nominated ticket's row (from TicketsService::loadTicket()), whose properties the new ticket copies |
| `$actorId` | `int` |  | the analyst doing the merge, for the audit row |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 458 | `mergeCreateTargetTicket` | **insert** [tickets](/code/tables/tickets.md) (`ticket_number`, `subject`, `status_id`, `priority_id`, `department_id`, `ticket_type_id`, `assigned_analyst_id`, `origin_id`, `user_id`, `owner_id`, `tenant_id`, `created_datetime`) |  |

## Calls

[mergeAudit()](/code/functions/mergeAudit.md)

## Called from

- [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) - in [mergeTickets()](/code/functions/mergeTickets.md)

## Traps

> [!WARNING]
> **Line 449, in `mergeCreateTargetTicket`:** the install's numbering, never a number made here - see `messagingGenerateTicketNumber()` for what went wrong when it was.

