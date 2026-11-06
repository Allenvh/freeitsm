# sla_compute_response()

Compute response-time SLA state. The clock stops at the first response, where "first response" is defined per the sla_first_response_definition setting. v1 supports 'status_change' (first audit row that moves status away from the default) for all three options — outbound-email detection is deferred to a follow-up (see docs/sla.md).

```php
function sla_compute_response(
    PDO $conn,
    array $ticket,
    array $priority,
    array $calendar,
    array $timeline,
    array $settings
): array
```

Defined in [includes/sla.php](/code/files/includes/sla.php.md), line 168.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticket` | `array` |  | the ticket row as loaded by `sla_get_state()` (not read in v1) |
| `$priority` | `array` |  | the ticket_priorities row; sla_response_minutes is the target |
| `$calendar` | `array` |  | the SLA calendar from `sla_load_calendar()`: timezone, hours, holidays |
| `$timeline` | `array` |  | status segments [{start: DateTimeImmutable UTC, status: name, pauses: bool}], oldest first |
| `$settings` | `array` |  | the SLA settings from `sla_load_settings()` (not read in v1 - every definition uses status change) |

**Returns:** `array`

## Calls

[sla_elapsed_business_minutes()](/code/functions/sla_elapsed_business_minutes.md)

## Called from

- [includes/sla.php](/code/files/includes/sla.php.md) - in [sla_get_state()](/code/functions/sla_get_state.md)

