# apiTicketSelect()

The SELECT for tickets, with status, priority, type, origin, department, analyst, requester and company names.

```php
function apiTicketSelect(): string
```

Defined in [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), line 37.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `apiTicketSelect` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [ticket_types](/code/tables/ticket_types.md), read [ticket_origins](/code/tables/ticket_origins.md), read [departments](/code/tables/departments.md), read [analysts](/code/tables/analysts.md), read [users](/code/tables/users.md), read [tenants](/code/tables/tenants.md) |  |

## Called from

- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiLoadTicket()](/code/functions/apiLoadTicket.md), [apiTicketsList()](/code/functions/apiTicketsList.md)
- [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md)

