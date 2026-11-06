# ticketTenantId()

The company a ticket belongs to, or null. Used to resolve both of the above.

```php
function ticketTenantId(PDO $conn, int $ticketId): ?int
```

Defined in [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), line 282.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 285 | `ticketTenantId` | read [tickets](/code/tables/tickets.md) |  |

## Called from

- [api/tickets/get_time_entries.php](/code/files/api/tickets/get_time_entries.php.md)
- [api/tickets/save_time_entry.php](/code/files/api/tickets/save_time_entry.php.md)
- [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md)
- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiRequireTimeTracking()](/code/functions/apiRequireTimeTracking.md)

