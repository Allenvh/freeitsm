# sla_resolve_recipients()

Expand a rule into a unique list of recipient email addresses. Resolves assignee, department-team members, named analyst, and the explicit emails list; dedupes case-insensitively.

```php
function sla_resolve_recipients(PDO $conn, int $ticketId, array $rule): array
```

Defined in [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md), line 241.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$rule` | `array` |  | the sla_notification_rules row: notify_assignee, notify_department_teams, notify_analyst_id, notify_emails |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 245 | `sla_resolve_recipients` | read [tickets](/code/tables/tickets.md), read [analysts](/code/tables/analysts.md) |  |
| 257 | `sla_resolve_recipients` | read [tickets](/code/tables/tickets.md), read [department_teams](/code/tables/department_teams.md), read [analyst_teams](/code/tables/analyst_teams.md), read [analysts](/code/tables/analysts.md) |  |
| 272 | `sla_resolve_recipients` | read [analysts](/code/tables/analysts.md) |  |

## Called from

- [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) - in [sla_run_breach_check()](/code/functions/sla_run_breach_check.md)

