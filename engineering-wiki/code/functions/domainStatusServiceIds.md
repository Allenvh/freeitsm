# domainStatusServiceIds()

The services linked to a domain, ids only (install-wide records).

```php
function domainStatusServiceIds(PDO $conn, int $domainId): array
```

Defined in [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md), line 104.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` | `int` |  | the domain whose active linked services to list |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 106 | `domainStatusServiceIds` | read [domain_status_services](/code/tables/domain_status_services.md), read [status_services](/code/tables/status_services.md) |  |

## Called from

- [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) - in [domainStatusState()](/code/functions/domainStatusState.md), [domainStatusRaise()](/code/functions/domainStatusRaise.md)

