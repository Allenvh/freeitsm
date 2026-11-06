# domainStatusReady()

True once Database Verification has created the domain_status_incidents and domain_status_services tables; checked once per request.

```php
function domainStatusReady(PDO $conn): bool
```

Defined in [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md), line 43.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 | `domainStatusReady` | read [domain_status_incidents](/code/tables/domain_status_incidents.md) |  |
| 49 | `domainStatusReady` | read [domain_status_services](/code/tables/domain_status_services.md) |  |

## Called from

- [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) - in [domainStatusState()](/code/functions/domainStatusState.md), [domainStatusRaise()](/code/functions/domainStatusRaise.md), [domainStatusRun()](/code/functions/domainStatusRun.md)

