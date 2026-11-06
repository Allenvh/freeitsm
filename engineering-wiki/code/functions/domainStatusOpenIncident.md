# domainStatusOpenIncident()

The incident this domain raised that is still open, or null.

```php
function domainStatusOpenIncident(PDO $conn, int $domainId): ?array
```

Defined in [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md), line 117.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` | `int` |  | the domain whose open, domain-raised incident to find |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 120 | `domainStatusOpenIncident` | read [domain_status_incidents](/code/tables/domain_status_incidents.md), read [status_incidents](/code/tables/status_incidents.md) |  |

## Called from

- [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) - in [domainStatusState()](/code/functions/domainStatusState.md), [domainStatusRaise()](/code/functions/domainStatusRaise.md), [domainStatusRun()](/code/functions/domainStatusRun.md)

