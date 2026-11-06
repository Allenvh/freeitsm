# apiStatusIncidentsList()

GET /service-status/incidents

```php
function apiStatusIncidentsList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), line 278.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 287 | `apiStatusIncidentsList` | read [status_incident_services](/code/tables/status_incident_services.md) |  |
| 305 | `apiStatusIncidentsList` | read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |

## Calls

[apiPagination()](/code/functions/apiPagination.md), [apiParseDate()](/code/functions/apiParseDate.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeIncident()](/code/functions/apiSerializeIncident.md), [apiServiceIncidentSelect()](/code/functions/apiServiceIncidentSelect.md)

*Nothing calls this function by name.*

