# rpStatusIncidentLog()

Every incident UPDATE in the range, oldest first, with the services it named and their worst impact - the incident log in Enrique's example. The update's comment goes underneath the row, full width.

```php
function rpStatusIncidentLog(PDO $conn, int $analystId, array $o, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md), line 129.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$o` | `array` |  | the block's cleaned options: internal (include internal updates), comments (show each update's comment) |
| `$range` | `array` |  | the pack's range from `rpResolveRange()` - from_utc and to_utc are used |
| `$tenant` |  |  | the pack's company (not used - Service Status has no company) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 133 | `rpStatusIncidentLog` | read [status_incident_updates](/code/tables/status_incident_updates.md), read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md), read [analysts](/code/tables/analysts.md) |  |
| 151 | `rpStatusIncidentLog` | read [status_incident_update_services](/code/tables/status_incident_update_services.md), read [status_services](/code/tables/status_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## Calls

[rpFmtDateTime()](/code/functions/rpFmtDateTime.md), [rpPlainText()](/code/functions/rpPlainText.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

