# rpStatusIncidentSummary()

Incidents overlapping the range: title, status pill, affected services as chips.

```php
function rpStatusIncidentSummary(PDO $conn, int $analystId, array $o, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md), line 201.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$o` | `array` |  | the block's cleaned options (none are read) |
| `$range` | `array` |  | the pack's range from `rpResolveRange()` - from_utc and to_utc are used |
| `$tenant` |  |  | the pack's company (not used - Service Status has no company) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 204 | `rpStatusIncidentSummary` | read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 219 | `rpStatusIncidentSummary` | read [status_incident_services](/code/tables/status_incident_services.md), read [status_services](/code/tables/status_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## Calls

[rpFmtDateTime()](/code/functions/rpFmtDateTime.md), [rpHumanDuration()](/code/functions/rpHumanDuration.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

