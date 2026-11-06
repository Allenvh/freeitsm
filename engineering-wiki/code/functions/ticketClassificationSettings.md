# ticketClassificationSettings()

All three answers at once, for a page that is about to render a ticket.

One call rather than three so a caller cannot accidentally resolve two of them
against one company and the third against another.

```php
function ticketClassificationSettings(PDO $conn, ?int $tenantId): array
```

Defined in [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), line 272.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array{category:bool, closure_category:bool, resolution_code:bool}

## Calls

[ticketCategoryOn()](/code/functions/ticketCategoryOn.md), [ticketClosureCategoryOn()](/code/functions/ticketClosureCategoryOn.md), [ticketResolutionCodeOn()](/code/functions/ticketResolutionCodeOn.md)

## Called from

- [api/tickets/get_ticket_classification.php](/code/files/api/tickets/get_ticket_classification.php.md)

