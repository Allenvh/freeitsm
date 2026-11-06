# ssPortalUpdatesEnabled()

Are incidents shown on the portal at all?

```php
function ssPortalUpdatesEnabled(PDO $conn): bool
```

Defined in [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md), line 74.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[ssPortalSetting()](/code/functions/ssPortalSetting.md)

## Called from

- [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md)
- [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md) - in [ssPortalIncidents()](/code/functions/ssPortalIncidents.md), [ssPortalIncidentUpdates()](/code/functions/ssPortalIncidentUpdates.md)
- [service-status/index.php](/code/files/service-status/index.php.md)

