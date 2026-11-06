# defaultImpactLevel()

The impact level a service shows when nothing is wrong with it.

```php
function defaultImpactLevel(PDO $conn): array
```

Defined in [includes/service_impact_levels.php](/code/files/includes/service_impact_levels.php.md), line 29.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array{id:?int,name:string,colour:?string} The default impact level. Per-connection cached — it is read several times to build one page.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 | `defaultImpactLevel` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 44 | `defaultImpactLevel` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## Called from

- [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md)
- [api/service-status/get_dashboard.php](/code/files/api/service-status/get_dashboard.php.md)
- [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) - in [apiServiceStatusSubquery()](/code/functions/apiServiceStatusSubquery.md), [apiSerializeService()](/code/functions/apiSerializeService.md)
- [includes/services/service_status.php](/code/files/includes/services/service_status.php.md) - in `ServiceStatusService::validateIncidentServices`

