# apiSerializeTicket()

Shapes a ticket row for the API - number, subject, status (with is_closed), priority, type, origin, department, analyst, requester and the rest of the record.

```php
function apiSerializeTicket(array $r): array
```

Defined in [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), line 64.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | the tickets row as `apiTicketSelect()` loads it, with the lookup, analyst, requester and company names |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiTicketsGet()](/code/functions/apiTicketsGet.md), [apiTicketsCreate()](/code/functions/apiTicketsCreate.md), [apiTicketsUpdate()](/code/functions/apiTicketsUpdate.md), [apiTicketsRestore()](/code/functions/apiTicketsRestore.md)
- [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::fullObjectLoaders`

