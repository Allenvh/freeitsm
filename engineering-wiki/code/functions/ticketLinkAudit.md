# ticketLinkAudit()

Writes one ticket_audit row for a link change, with no old value and the new value cut to 500 characters.

```php
function ticketLinkAudit(
    PDO $conn,
    int $ticketId,
    int $analystId,
    string $field,
    string $newVal
): void
```

Defined in [includes/ticket_links.php](/code/files/includes/ticket_links.php.md), line 52.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$field` | `string` |  | the audit field: 'Linked ticket' or 'Unlinked ticket' |
| `$newVal` | `string` |  | what to record, e.g. 'Parent of ' and the other ticket's number; cut to 500 characters |

**Returns:** `void`

**Writes:** ticket_audit - the link or unlink shows in the ticket's history

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 | `ticketLinkAudit` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## Called from

- [includes/ticket_links.php](/code/files/includes/ticket_links.php.md) - in [ticketLinkCreate()](/code/functions/ticketLinkCreate.md), [ticketLinkRemove()](/code/functions/ticketLinkRemove.md)

