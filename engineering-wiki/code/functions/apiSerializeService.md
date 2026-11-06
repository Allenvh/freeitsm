# apiSerializeService()

Shapes a service row for the API, with its current status (the default impact level when nothing is affecting it) and that status's colour and severity.

```php
function apiSerializeService(PDO $conn, array $r): array
```

Defined in [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), line 82.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$r` | `array` |  | a status_services row, with current_status from `apiServiceStatusSubquery()` (null = default level) |

**Returns:** `array`

## Calls

[apiServiceStatusImpactMeta()](/code/functions/apiServiceStatusImpactMeta.md), [defaultImpactLevel()](/code/functions/defaultImpactLevel.md)

## Called from

- [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) - in [apiStatusServicesList()](/code/functions/apiStatusServicesList.md), [apiStatusServicesGet()](/code/functions/apiStatusServicesGet.md), [apiStatusServicesCreate()](/code/functions/apiStatusServicesCreate.md), [apiStatusServicesUpdate()](/code/functions/apiStatusServicesUpdate.md)

