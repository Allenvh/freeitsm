# apiLoadService()

Loads one service row with its current status, or answers 404 and stops.

```php
function apiLoadService(PDO $conn, int $serviceId): array
```

Defined in [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), line 104.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$serviceId` | `int` |  | the status_services id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 106 | `apiLoadService` | read [status_services](/code/tables/status_services.md) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiServiceStatusSubquery()](/code/functions/apiServiceStatusSubquery.md)

## Called from

- [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) - in [apiStatusServicesGet()](/code/functions/apiStatusServicesGet.md), [apiStatusServicesCreate()](/code/functions/apiStatusServicesCreate.md), [apiStatusServicesUpdate()](/code/functions/apiStatusServicesUpdate.md)

