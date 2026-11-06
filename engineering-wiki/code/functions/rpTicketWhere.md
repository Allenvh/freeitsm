# rpTicketWhere()

WHERE for "tickets in this period, for this company, not trashed".

```php
function rpTicketWhere(PDO $conn, int $analystId, string $basis, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md), line 45.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$basis` | `string` |  | which tickets: 'created' or 'closed' in the range, or 'open' at its end |
| `$range` | `array` |  | the pack's range from `rpResolveRange()` - from_utc and to_utc are used |
| `$tenant` |  |  | the pack's company: 'active', 'all' or a company id |

**Returns:** array{0:string,1:array}

## Calls

[rpTenantClause()](/code/functions/rpTenantClause.md)

## Called from

- [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) - in [rpTicketsBreakdown()](/code/functions/rpTicketsBreakdown.md), [rpTicketsTrend()](/code/functions/rpTicketsTrend.md), [rpTicketsKpis()](/code/functions/rpTicketsKpis.md), [rpTicketsList()](/code/functions/rpTicketsList.md)

