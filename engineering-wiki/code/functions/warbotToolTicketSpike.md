# warbotToolTicketSpike()

ticket_spike: tickets raised in the last N minutes against the average for the same time window over the previous seven days, calling three times the usual rate a spike, plus the newest subjects.

```php
function warbotToolTicketSpike(PDO $conn, array $args, int $analystId): string
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 701.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$args` | `array` |  | the model's arguments - untrusted: minutes (the window, 5-720, default 60) |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 706 | `warbotToolTicketSpike` | read [tickets](/code/tables/tickets.md) |  |
| 719 | `warbotToolTicketSpike` | read [tickets](/code/tables/tickets.md) |  |
| 742 | `warbotToolTicketSpike` | read [tickets](/code/tables/tickets.md) |  |

## Calls

[ticketAiSubjectSql()](/code/functions/ticketAiSubjectSql.md)

*Nothing calls this function by name.*

