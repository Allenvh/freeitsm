# ssPortalIncidentUpdates()

The EXTERNAL updates on one incident, oldest first — the progression from "we are looking at it" to "it is fixed", which is what a status page is for.

⚠️ `is_internal = 0` is not optional and is not a caller's decision. It is
here, in the query, because this function is the only thing the portal calls.

Returns [] when the feature is off, or when the incident has no external
updates — which is the same answer as "no such incident", deliberately.

```php
function ssPortalIncidentUpdates(PDO $conn, int $incidentId): array
```

Defined in [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md), line 194.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$incidentId` | `int` |  | the status_incidents row whose external updates to list |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 201 | `ssPortalIncidentUpdates` | read [status_incident_updates](/code/tables/status_incident_updates.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |

## Calls

[ssPortalUpdatesEnabled()](/code/functions/ssPortalUpdatesEnabled.md)

## Called from

- [api/self-service/get_incident_updates.php](/code/files/api/self-service/get_incident_updates.php.md)

