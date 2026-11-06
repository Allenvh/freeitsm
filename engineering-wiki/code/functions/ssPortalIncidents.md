# ssPortalIncidents()

Incidents the portal may show, newest first, each with its EXTERNAL updates.

Returns [] when the feature is switched off, which is the default — so a
caller that forgets to check gets nothing rather than everything.

```php
function ssPortalIncidents(PDO $conn, int $limit = 25): array
```

Defined in [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md), line 87.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$limit` | `int` | `25` | the most incidents to return |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 101 | `ssPortalIncidents` | read [status_incident_updates](/code/tables/status_incident_updates.md) |  |
| 118 | `ssPortalIncidents` | read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 139 | `ssPortalIncidents` | read [status_incident_services](/code/tables/status_incident_services.md), read [status_services](/code/tables/status_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 166 | `ssPortalIncidents` | read [status_incident_updates](/code/tables/status_incident_updates.md) |  |

## Calls

[ssPortalSettings()](/code/functions/ssPortalSettings.md), [ssPortalUpdatesEnabled()](/code/functions/ssPortalUpdatesEnabled.md)

## Called from

- [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md)

