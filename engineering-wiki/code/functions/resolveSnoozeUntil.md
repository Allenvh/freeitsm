# resolveSnoozeUntil()

Turn a preset (or a custom local datetime) into the UTC instant to store.

Presets are resolved server-side rather than in the browser so that "tomorrow
morning" means the same thing however the request arrived, and so the install's
wake hour is applied in one place. `$localZone` is the analyst's display zone
(Tz::current()); the arithmetic happens there and converts to UTC at the end,
which is what makes "tomorrow at 9" survive a DST boundary.

```php
function resolveSnoozeUntil(
    PDO $conn,
    string $preset,
    ?string $customLocal,
    string $localZone
): string
```

Defined in [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), line 156.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$preset` | `string` |  | three_hours \| tonight \| tomorrow \| next_week \| custom |
| `$customLocal` | `?string` |  | 'Y-m-d H:i' in the analyst's zone, required when `$preset` is custom |
| `$localZone` | `string` |  | the analyst's display time zone name (Tz::current()), e.g. 'Europe/London' |

**Returns:** string UTC datetime, 'Y-m-d H:i:s'

## Calls

[snoozeWakeHour()](/code/functions/snoozeWakeHour.md)

## Called from

- [api/tickets/snooze_ticket.php](/code/files/api/tickets/snooze_ticket.php.md)

