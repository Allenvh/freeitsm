# sla_get_state()

Compute the SLA state of a single ticket. Returns: [ 'enabled' => bool, 'reason_disabled' => ?string, 'priority' => ?array, 'calendar' => ?array, 'response' => ?array (target_minutes, elapsed_minutes, remaining_minutes, percent, breached, achieved_at, achieved_minutes), 'resolution' => ?array (same shape as response, or null if no target), ]

If `enabled` is false, the other fields are best-effort / informational only.

```php
function sla_get_state(PDO $conn, int $ticket_id): array
```

Defined in [includes/sla.php](/code/files/includes/sla.php.md), line 38.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticket_id` | `int` |  | the ticket (tickets.id) to work out |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 | `sla_get_state` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 79 | `sla_get_state` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 97 | `sla_get_state` | read [sla_calendars](/code/tables/sla_calendars.md) |  |
| 115 | `sla_get_state` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 118 | `sla_get_state` | read [ticket_audit](/code/tables/ticket_audit.md) |  |
| 127 | `sla_get_state` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## Calls

[sla_compute_resolution()](/code/functions/sla_compute_resolution.md), [sla_compute_response()](/code/functions/sla_compute_response.md), [sla_load_calendar()](/code/functions/sla_load_calendar.md), [sla_load_settings()](/code/functions/sla_load_settings.md)

## Called from

- [api/tickets/get_ticket_sla.php](/code/files/api/tickets/get_ticket_sla.php.md)
- [api/tickets/get_tickets_sla_batch.php](/code/files/api/tickets/get_tickets_sla_batch.php.md)
- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiTicketSlaGet()](/code/functions/apiTicketSlaGet.md)
- [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) - in [sla_run_breach_check()](/code/functions/sla_run_breach_check.md)

