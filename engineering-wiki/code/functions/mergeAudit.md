# mergeAudit()

Writes one ticket_audit row for a merge step, best-effort - a failed audit write never stops the merge. An actor id of 0 is stored as NULL.

```php
function mergeAudit(
    PDO $conn,
    int $ticketId,
    int $actorId,
    string $field,
    ?string $old,
    ?string $new
): void
```

Defined in [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), line 650.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$actorId` | `int` |  | the analyst doing the merge; 0 is stored as NULL |
| `$field` | `string` |  | the audit field: 'Ticket Created', 'Merged' or 'Merge undone' |
| `$old` | `?string` |  | the value before, or null |
| `$new` | `?string` |  | the value after, or null |

**Returns:** `void`

**Writes:** ticket_audit - the merge shows in each ticket's history

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 653 | `mergeAudit` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## Called from

- [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) - in [unmergeTicket()](/code/functions/unmergeTicket.md), [mergeCreateTargetTicket()](/code/functions/mergeCreateTargetTicket.md), [mergeOneTicket()](/code/functions/mergeOneTicket.md)

