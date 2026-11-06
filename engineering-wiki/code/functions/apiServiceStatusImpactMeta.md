# apiServiceStatusImpactMeta()

Every impact level's colour and severity order, keyed by name - read once per request and cached.

```php
function apiServiceStatusImpactMeta(PDO $conn): array
```

Defined in [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), line 65.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 69 | `apiServiceStatusImpactMeta` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## Called from

- [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) - in [apiSerializeService()](/code/functions/apiSerializeService.md)

