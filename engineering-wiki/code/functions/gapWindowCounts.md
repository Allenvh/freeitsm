# gapWindowCounts()

How many tickets are in the window, and how many already have an embedding.

```php
function gapWindowCounts(PDO $conn, int $analystId, int $lookbackDays): array
```

Defined in [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md), line 91.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$lookbackDays` | `int` |  | how many days back from now a ticket's closed_datetime may be |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 100 | `gapWindowCounts` | read [knowledge_gap_tickets](/code/tables/knowledge_gap_tickets.md) |  |

## Calls

[gapWindowSql()](/code/functions/gapWindowSql.md)

## Called from

- [api/knowledge/analyse_gaps.php](/code/files/api/knowledge/analyse_gaps.php.md)

