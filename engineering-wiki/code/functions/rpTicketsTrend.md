# rpTicketsTrend()

Tickets block: a chart of tickets created and/or closed per day, week or month across the period.

Bucketed in PHP from the timestamps, since the buckets are local days and the database
only knows UTC.

```php
function rpTicketsTrend(PDO $conn, int $analystId, array $o, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md), line 188.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$o` | `array` |  | the block's cleaned options: grouping, series ('created', 'closed' or 'both'), chart |
| `$range` | `array` |  | the pack's range from `rpResolveRange()` |
| `$tenant` |  |  | the pack's company: 'active', 'all' or a company id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 198 | `rpTicketsTrend` | read [tickets](/code/tables/tickets.md) |  |

## Calls

[rpBucketCount()](/code/functions/rpBucketCount.md), [rpTicketWhere()](/code/functions/rpTicketWhere.md), [rpTimeBuckets()](/code/functions/rpTimeBuckets.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

