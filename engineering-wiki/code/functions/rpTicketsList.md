# rpTicketsList()

Tickets block: a table of the tickets in the period, with the columns the block asks for, up to its limit.

```php
function rpTicketsList(PDO $conn, int $analystId, array $o, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md), line 254.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$o` | `array` |  | the block's cleaned options: basis, limit, and the column switches status, priority, analyst, category, closed |
| `$range` | `array` |  | the pack's range from `rpResolveRange()` |
| `$tenant` |  |  | the pack's company: 'active', 'all' or a company id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 257 | `rpTicketsList` | read [tickets](/code/tables/tickets.md) |  |
| 261 | `rpTicketsList` | read [analysts](/code/tables/analysts.md), read [ticket_categories](/code/tables/ticket_categories.md) |  |

## Calls

[rpFmtDateTime()](/code/functions/rpFmtDateTime.md), [rpTicketWhere()](/code/functions/rpTicketWhere.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

