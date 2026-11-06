# sla_find_matching_rule()

Find the most-specific rule matching this ticket + trigger + target.

Resolution order:
  1. Per-dept rule with this exact target ('response' or 'resolution')
  2. Per-dept rule with target='both'
  3. Default rule (NULL dept) with this exact target
  4. Default rule (NULL dept) with target='both'

'both' rules match either target — they're the "applies to all SLAs"
convenience shortcut.

```php
function sla_find_matching_rule(
    PDO $conn,
    int $ticketId,
    string $trigger,
    string $targetType
): ?array
```

Defined in [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md), line 213.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$trigger` | `string` |  | 'warning' or 'breach' |
| `$targetType` | `string` |  | 'response' or 'resolution' |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 214 | `sla_find_matching_rule` | read [tickets](/code/tables/tickets.md) |  |
| 220 | `sla_find_matching_rule` | read [sla_notification_rules](/code/tables/sla_notification_rules.md) |  |

## Called from

- [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) - in [sla_run_breach_check()](/code/functions/sla_run_breach_check.md)

