# ticketAiExclusionSql()

For AI features that read MANY tickets (problem root cause, suggested problems, knowledge gap analysis, Warbot): an SQL condition that leaves the confidential ones out, or '' when the policy allows them or the column does not exist yet. Pass the tickets table's alias.

```php
function ticketAiExclusionSql(PDO $conn, string $alias = 't'): string
```

Defined in [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), line 214.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'t'` | the alias the calling query gives the tickets table |

**Returns:** `string`

## Calls

[ticketAiConfidentialPolicy()](/code/functions/ticketAiConfidentialPolicy.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)

## Called from

- [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md)
- [api/problem-management/ai_root_cause.php](/code/files/api/problem-management/ai_root_cause.php.md)
- [api/problem-management/ai_suggest_problem.php](/code/files/api/problem-management/ai_suggest_problem.php.md)
- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapWindowSql()](/code/functions/gapWindowSql.md)

