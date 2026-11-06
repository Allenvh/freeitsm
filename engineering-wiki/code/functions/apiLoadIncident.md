# apiLoadIncident()

Loads one incident row (apiServiceIncidentSelect), or answers 404 and stops.

```php
function apiLoadIncident(PDO $conn, int $incidentId): array
```

Defined in [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), line 179.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$incidentId` | `int` |  | the status_incidents id |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md), [apiServiceIncidentSelect()](/code/functions/apiServiceIncidentSelect.md)

## Called from

- [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) - in [apiStatusIncidentsGet()](/code/functions/apiStatusIncidentsGet.md), [apiStatusIncidentsCreate()](/code/functions/apiStatusIncidentsCreate.md), [apiStatusIncidentsUpdate()](/code/functions/apiStatusIncidentsUpdate.md)

