# domainStatusImpactId()

The impact level to record. The chosen one if it still exists; otherwise the MOST severe active level that counts as downtime.

🔴 Not Service Status's own default level: on a stock install that is
"Operational", so falling back to it would raise an incident that says
the service is fine.

```php
function domainStatusImpactId(PDO $conn, int $chosen): int
```

Defined in [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md), line 216.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$chosen` | `int` |  | the domain_status_impact setting as an id; 0 means none chosen |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 219 | `domainStatusImpactId` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 225 | `domainStatusImpactId` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## Called from

- [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) - in [domainStatusRaise()](/code/functions/domainStatusRaise.md)

