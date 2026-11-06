# apiSerializeIncident()

Shapes an incident row for the API, with every service it affects and the impact on each, worst first.

```php
function apiSerializeIncident(PDO $conn, array $r): array
```

Defined in [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), line 135.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$r` | `array` |  | the status_incidents row as `apiServiceIncidentSelect()` loads it, with status and creator names |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 137 | `apiSerializeIncident` | read [status_incident_services](/code/tables/status_incident_services.md), read [status_services](/code/tables/status_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) - in [apiStatusServicesGet()](/code/functions/apiStatusServicesGet.md), [apiStatusIncidentsList()](/code/functions/apiStatusIncidentsList.md), [apiStatusIncidentsGet()](/code/functions/apiStatusIncidentsGet.md), [apiStatusIncidentsCreate()](/code/functions/apiStatusIncidentsCreate.md), [apiStatusIncidentsUpdate()](/code/functions/apiStatusIncidentsUpdate.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::fullObjectLoaders`

