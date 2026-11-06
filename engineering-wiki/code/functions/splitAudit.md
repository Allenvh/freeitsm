# splitAudit()

Writes one ticket_audit row for a split step, best-effort - a failed audit write never stops the split. An actor id of 0 is stored as NULL.

```php
function splitAudit(
    PDO $conn,
    int $ticketId,
    int $actorId,
    string $field,
    ?string $old,
    ?string $new
): void
```

Defined in [includes/ticket_split.php](/code/files/includes/ticket_split.php.md), line 345.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$actorId` | `int` |  | the analyst splitting or undoing; 0 is stored as NULL |
| `$field` | `string` |  | the audit field name, e.g. 'Split' or 'Split undone' |
| `$old` | `?string` |  | the old value, normally null |
| `$new` | `?string` |  | the text shown in the history |

**Returns:** `void`

**Writes:** ticket_audit - the split shows in each ticket's history

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 348 | `splitAudit` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## Called from

- [includes/ticket_split.php](/code/files/includes/ticket_split.php.md) - in [splitTicket()](/code/functions/splitTicket.md), [undoSplit()](/code/functions/undoSplit.md)

