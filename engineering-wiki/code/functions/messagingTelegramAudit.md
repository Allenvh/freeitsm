# messagingTelegramAudit()

Record what happened on the ticket's audit trail (Audit tab), as automation: analyst_id NULL is the established "nobody did this, the system did" marker (see the ticket_audit comment in freeitsm.sql). Best-effort.

```php
function messagingTelegramAudit(
    PDO $conn,
    int $ticketId,
    string $field,
    ?string $old,
    string $new
): void
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 775.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$field` | `string` |  | the audit field name shown, e.g. 'Requester' |
| `$old` | `?string` |  | the old value; null for none (cut to 500 characters) |
| `$new` | `string` |  | the new value or what happened (cut to 500 characters) |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 778 | `messagingTelegramAudit` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [messagingTelegramLinkPhone()](/code/functions/messagingTelegramLinkPhone.md)

