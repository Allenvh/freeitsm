# warbotToolServiceStatus()

service_status: the active Service Status services and up to ten open service-status incidents, with the services each affects and its latest note.

```php
function warbotToolServiceStatus(PDO $conn, array $args, int $analystId): string
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 446.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$args` | `array` |  | the model's arguments; this tool takes none |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 449 | `warbotToolServiceStatus` | read [status_services](/code/tables/status_services.md) |  |
| 456 | `warbotToolServiceStatus` | read [status_incident_services](/code/tables/status_incident_services.md), read [status_services](/code/tables/status_services.md), read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |

*Nothing calls this function by name.*

