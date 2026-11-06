# generateTicketNumber()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md), [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)

⚠️ One of THREE copies of this, now all delegating to the single engine in includes/ticket_numbering.php (GH #71). Left as a wrapper rather than deleted so the call sites in this file keep reading naturally; the format, the counter and the uniqueness check all live in one place.

```php
function generateTicketNumber($conn, ?int $tenantId = null)
```

Defined in [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md), line 328.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` | `null` | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

## Called from

- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)

