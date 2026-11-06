# apiServiceStatusSubquery()

get_dashboard.php's exact worst-open-impact subquery, as a SQL fragment.

The "no open incident" fallback is the configured default impact level, not the
literal 'Operational' (GH #70). Quoted rather than bound because this fragment is
pasted into several statements whose own placeholders are positional.

```php
function apiServiceStatusSubquery(PDO $conn): string
```

Defined in [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), line 52.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 54 | `apiServiceStatusSubquery` | read [status_incident_services](/code/tables/status_incident_services.md), read [status_incidents](/code/tables/status_incidents.md), read [service_impact_levels](/code/tables/service_impact_levels.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |

## Calls

[defaultImpactLevel()](/code/functions/defaultImpactLevel.md)

## Called from

- [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) - in [apiLoadService()](/code/functions/apiLoadService.md), [apiStatusServicesList()](/code/functions/apiStatusServicesList.md)

