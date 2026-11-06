# apiServiceStatusFail()

Map a ServiceError raised by the shared service to the API's error response.

```php
function apiServiceStatusFail(ServiceError $e): void
```

Defined in [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), line 37.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$e` | `ServiceError` |  | the error ServiceStatusService threw - its kind picks the status, errorCode and message pass through |

**Returns:** `void`

## Calls

[apiError()](/code/functions/apiError.md), [serviceErrorHttpStatus()](/code/functions/serviceErrorHttpStatus.md)

## Called from

- [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) - in [apiStatusServicesCreate()](/code/functions/apiStatusServicesCreate.md), [apiStatusServicesUpdate()](/code/functions/apiStatusServicesUpdate.md), [apiStatusServicesDelete()](/code/functions/apiStatusServicesDelete.md), [apiStatusIncidentsCreate()](/code/functions/apiStatusIncidentsCreate.md), [apiStatusIncidentsUpdate()](/code/functions/apiStatusIncidentsUpdate.md), [apiStatusIncidentsDelete()](/code/functions/apiStatusIncidentsDelete.md)

