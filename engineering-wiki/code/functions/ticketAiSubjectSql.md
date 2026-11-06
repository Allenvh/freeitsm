# ticketAiSubjectSql()

The ticket's subject as an AI may see it: 'Confidential ticket' in place of a confidential one's (under the block policy), so a count or a list stays true while its content stays home. An SQL expression; pass the tickets alias.

```php
function ticketAiSubjectSql(PDO $conn, string $alias = 't'): string
```

Defined in [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), line 227.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'t'` | the alias the calling query gives the tickets table |

**Returns:** `string`

## Calls

[ticketAiConfidentialPolicy()](/code/functions/ticketAiConfidentialPolicy.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)

## Called from

- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotToolOpenIncidents()](/code/functions/warbotToolOpenIncidents.md), [warbotToolTicketSpike()](/code/functions/warbotToolTicketSpike.md), [warbotToolRelatedTickets()](/code/functions/warbotToolRelatedTickets.md)

