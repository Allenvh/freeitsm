# sla_run_breach_check()

Main cron entry. Returns a summary so the cron wrapper can log it.

```php
function sla_run_breach_check(PDO $conn): array
```

Defined in [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md), line 24.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `sla_run_breach_check` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## Calls

[sla_emit_workflow_event()](/code/functions/sla_emit_workflow_event.md), [sla_find_matching_rule()](/code/functions/sla_find_matching_rule.md), [sla_get_state()](/code/functions/sla_get_state.md), [sla_load_settings()](/code/functions/sla_load_settings.md), [sla_mark_notification_sent()](/code/functions/sla_mark_notification_sent.md), [sla_notification_already_sent()](/code/functions/sla_notification_already_sent.md), [sla_resolve_recipients()](/code/functions/sla_resolve_recipients.md), [sla_send_breach_email()](/code/functions/sla_send_breach_email.md)

## Called from

- [cron/sla_breach_check.php](/code/files/cron/sla_breach_check.php.md)

