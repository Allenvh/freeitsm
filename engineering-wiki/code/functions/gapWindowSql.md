# gapWindowSql()

The closed-ticket window this analyst can see.

Returns ['from','where','args'] as separate fragments — the cluster query has
to slot a LEFT JOIN between FROM and WHERE, and a single pre-joined blob makes
that impossible in a way that only breaks on one code path.

```php
function gapWindowSql(PDO $conn, int $analystId, int $lookbackDays): array
```

Defined in [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md), line 50.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$lookbackDays` | `int` |  | how many days back from now a ticket's closed_datetime may be |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 58 | `gapWindowSql` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## Calls

[activeTenantFilter()](/code/functions/activeTenantFilter.md), [ticketAiExclusionSql()](/code/functions/ticketAiExclusionSql.md)

## Called from

- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapWindowCounts()](/code/functions/gapWindowCounts.md), [gapEmbedBatch()](/code/functions/gapEmbedBatch.md), [gapAnalyse()](/code/functions/gapAnalyse.md)

