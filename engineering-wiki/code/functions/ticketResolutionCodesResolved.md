# ticketResolutionCodesResolved()

Resolution codes this company can see.

```php
function ticketResolutionCodesResolved(PDO $conn, ?int $tenantId, array $opts = []): array
```

Defined in [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md), line 316.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$opts` | `array` | `[]` | activeOnly — drop retired codes (default false) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 325 | `ticketResolutionCodesResolved` | read [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) |  |

## Calls

[getTenantConfigRows()](/code/functions/getTenantConfigRows.md)

## Called from

- [api/tickets/get_ticket_classification.php](/code/files/api/tickets/get_ticket_classification.php.md)

