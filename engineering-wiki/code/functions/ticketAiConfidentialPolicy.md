# ticketAiConfidentialPolicy()

Tickets -> Settings -> "Confidential tickets and AI": 'block' (the default - never send) or 'allow'. Never saved means block: an upgrade must not start sending confidential tickets to a provider it was not sending them to.

```php
function ticketAiConfidentialPolicy(PDO $conn): string
```

Defined in [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), line 186.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 189 | `ticketAiConfidentialPolicy` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md) - in [ticketAiAllowed()](/code/functions/ticketAiAllowed.md), [ticketAiExclusionSql()](/code/functions/ticketAiExclusionSql.md), [ticketAiSubjectSql()](/code/functions/ticketAiSubjectSql.md)

